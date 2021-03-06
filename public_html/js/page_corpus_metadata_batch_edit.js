var url = $.url(window.location.href);
var corpus_id = url.param('corpus');
var metadata_regex = [];
var metadata_user_regex = [];
var regex_columns = [];
var autosave = $.cookie("autosave_on") == 1;
var hot;
var hot_columns;
var changed_docs = {
    'corpus_id': corpus_id,
    docs: {}
};
var metadata_separators = [
    {key: "-", value: "-"},
    {key: "_", value: "_"}
];

var pattern;

function setupMetadataModal(){
    var data = {'corpus_id': corpus_id};

    var success = function(data) {
        var fields = [];
        var preview = [];
        var filenames = data['filenames'];
        var columns = data['columns'];

        for(var i = 0; i < columns.length; i++){
            fields.push({key: columns[i], value: columns[i]});
        }

        for(var i = 0; i < filenames.length; i++){
            preview.push(filenames[i].filename);
        }


        pattern = new PatternEditor("#pattern-editor", "#pattern-preview", "#pattern-result", fields, metadata_separators);
        pattern.setPreviewData(preview);
        pattern.render();

        $("#pattern-editor-add-row").click(
            function(){
                pattern.addPatternRow();
                pattern.update();
            });

        $("#submit-filenames").click(function(){
            var filenames = $.map($("#filenames").val().split("\n"), function(v,i){return v.trim()}).filter(Boolean);
            pattern.setPreviewData(filenames);
            pattern.render();
        });
    };

    doAjax("metadata_batch_edit_get_fields", data, success);
}


$(function() {
    getDocumentsWithMetadata();
    setupMetadataModal();
    loadMetadataFromFilename();

    $("#save_data_button").click(function(){
        document.body.style.cursor='wait';
        if(!jQuery.isEmptyObject(changed_docs.docs)){
            $(this).html("<img class='ajax_indicator' src='gfx/ajax.gif'/>");

            var complete = function(){
                document.body.style.cursor='default';
                transformSaveButton("normal");
            };

            doAjax("metadata_batch_edit_update", changed_docs, null, null, complete);
        } else{
            transformSaveButton("normal");
        }

        removeColors();
        document.body.style.cursor='default';
    });

    if(autosave){
        $(".autosave").prop('checked', true);
        $("#save_data_button").prop("disabled", true);
    } else{
        $(".autosave").prop('checked', false);
        $("#save_data_button").prop("disabled", false);
    }

    $(".autosave").click(function(){
        if($(this).prop('checked') === true){
            autosave = true;
            $.cookie("autosave_on", 1);
            $("#save_data_button").prop("disabled", true);
        } else{
            autosave = false;
            $.cookie("autosave_on", 0);
            $("#save_data_button").prop("disabled", false);
        }
    });

});


/**
 * Loop over filenames and change the appropriate columns in the matched rows.
 */
function checkFileNames(){
    var tableData = hot.getData();
    tableData.forEach(function(row, row_num){
            var regex_pattern = new RegExp(getMetadata(metadata_regex), "g");
            var filename = row.Filename;


            var match = regex_pattern.exec(filename);
            if(match !== null && match.length === (regex_columns.length + 1)){
                changeRowColor(row_num, 'colorized_green');
                regex_columns.forEach(function(column, c_index){
                    //Skip 'ignore sequence' tag.
                    if(column !== "Ignore sequence"){
                        changeRowData(row_num, column, match[c_index + 1]);
                    }
                });
            } else{
                changeRowColor(row_num, 'colorized_red');
            }
    });
}

function saveRegexPattern(){
    var regexData = pattern.getPattern();
    metadata_regex = [];
    regex_columns = [];
    regexData.forEach(function(value){
        metadata_regex.push(value.regex + value.delimiter);
        regex_columns.push(value.field);
    });
}

/**
 * Changes the data in the given column in a row.
 * @param row_num
 * @param column
 * @param match
 */
function changeRowData(row_num, column, match){
    if(column === "Subcorpus" || column === "Format" || column === "Status"){
        var id;
        hot_columns.forEach(function(value){
            if(value.data === column){
                value.chosenOptions.data.forEach(function(enum_val){
                    if(enum_val.label === match){
                        id = enum_val.id;
                    }
                });
            }
        });

        if(id !== null){
            match = id;
        } else{
            changeRowColor(row_num);
            return;
        }
    }
    hot.setDataAtRowProp(row_num, column, match);
}

/**
 * Changes the color of each cell in a row.
 * @param row_num
 * @param color
 */
function changeRowColor(row_num, color){
    for(var i = 0; i < hot.countCols(); i++){
        hot.setCellMeta(row_num, i, 'className', color);
    }
}

function removeColors(){
    for(var j = 0; j < (hot.getData()).length; j++){
        for(var i = 0; i < hot.countCols(); i++){
            hot.setCellMeta(j, i, 'className', '');
        }
    }
    hot.render();
}

/**
 * Check if field and token are selected in the "Load metadata from filename" modal.
 * @returns {*}
 */
function fieldTokenSelected(){
    var field = $(".field_select").val();
    var token = $(".token_select").val();

    if(field === "null" || token === "null"){
        var message;
        if(field === "null" && token === "null"){
            message = "Select the field and the token";
        } else if(field === "null"){
            message = "Select the field";
        } else if(token === "null"){
            message = "Select the token";
        }
        $(".metadata_modal_error").html("<strong>"+message+"</strong>");
        $(".metadata_modal_error").show();
        return false;
    } else{
        $(".field_select").val("null");
        $(".token_select").val("null");
        return {
            'field': field,
            'token': token
        };
    }
}

/**
 * Converts metadata from the array form to a string. Can be used for metadata_regex or user_metadata_regex.
 * @param metadata
 * @returns {string}
 */
function getMetadata(metadata){
    var metadata_str = "";
    metadata.forEach(function(value){
        metadata_str += value;
    });

    return metadata_str;
}

function loadMetadataFromFilename(){
    $("#confirm_metadata_load").click(function(){
        $("#load_metadata_modal").modal('hide');

        //Disable autosave
        autosave = false;
        $.cookie("autosave_on", 0);
        $(".autosave").prop('checked', false);
        transformSaveButton("metadata");
        saveRegexPattern();
        checkFileNames();
    });
}

/**
 *
 * @param style - two values - 'metadata' (red) or 'normal' (blue)
 */
function transformSaveButton(style){
    $("#save_data_button").prop("disabled", false);
    var _class;
    var text;
    if(style === "metadata"){
        _class = 'btn btn-danger';
        text = "Click to save metadata values extraced from filenames";
    } else if(style === "normal"){
        _class = 'btn btn-primary';
        text = "Save";
    }

    $("#save_data_button").attr('class', _class);
    $("#save_data_button").html(text);
}

/**
 * Convers the data into the format accepted by Handsontable.
 * @param $columns
 */
function getMetadataColumnNames(columns){
    var columnOrder = [];
    var columnHeaders = [];

    //Returns true if string is not empty.
    var notEmptyValidator = /^(?!\s*$).+/;

    columns.forEach(function (value, i) {
        var field_name = value['field'];
        //Makes the report_id field read-only.
        if(field_name === "Report_ID" || field_name === "Filename"){
            var data = {data: field_name,
                        readOnly: true};
        } else{
            var data = {data: field_name};

            //Lock the subcorpus column if there are no subcorpora assigned to the corpus.
            if(field_name === "Subcorpus" && value['field_values'] == null){
                data.readOnly = true;
            }

            //Adds a dropdown with accepted values for enumeration
            if(value['type'] === 'enum'){
                data.type = 'dropdown';
                data.source = [];
                if ( "field_ids" in value ){
                    data.renderer = customDropdownRenderer;
                    data.editor = "chosen";
                    data.chosenOptions = { data: []};
                    data.validator = notEmptyValidator;
                    data.allowInvalid = false;
                    for (var i=0; i<value['field_values'].length; i++){
                        data.chosenOptions.data.push({id: value['field_ids'][i], label: value['field_values'][i]});
                    }
                } else {
                    value['field_values'].forEach(function (val) {
                        data.source.push(val);
                    });
                }
            }

            //Prevents empty values in NOT NULL cells.
            if(value['null'] === "No"){
                data.validator = notEmptyValidator;
                data.allowInvalid = false;
            }
        }
        columnOrder.push(data);
        columnHeaders.push(field_name);
    });
    var columnData = {
        'columnHeaders': columnHeaders,
        'columnOrder': columnOrder
    };
    hot_columns = columnOrder;
    return columnData;
}

/**
 * Retrieves the list of metadata with their metadata.
 */
function getDocumentsWithMetadata(){
    var data = {'corpus_id': corpus_id};

    var success = function(data) {
            var colData = getMetadataColumnNames(data.columns);
            generateMetadataTable(data.documents, colData.columnHeaders, colData.columnOrder);

    };

    doAjax("metadata_batch_edit_get", data, success);
}

/**
 * Creates an instance of Handsontable.
 * @param data
 * @param colHeaders
 * @param columnOrder
 */

function generateMetadataTable(data, colHeaders, columnOrder){
    var container = $('#hot-container')[0];
    var searchFiled = $('#search_field')[0];

    hot = new Handsontable(container, {
        data: data,
        rowHeaders: true,
        colHeaders: colHeaders,
        filters: true,
        columnSorting: true,
        sortIndicator: true,
        wordWrap: false,
        manualColumnResize: true,
        modifyColWidth: function(width, col){
            if(width > 500){
                return 200
            }
        },
        search: true,
        columns: columnOrder,
        afterChange: function (change, source) {
            if(change !== null){
                change.forEach(function(value){
                    var row = {};
                    var report_id = data[value[0]].Report_ID;
                    row.value = value[3];
                    var field = value[1];
                    changed_docs.docs[report_id+"_"+field] = row;
                });

                var success = function() {
                    document.body.style.cursor='default'
                };

                var complete = function(data){
                    document.body.style.cursor='default'
                };

                if(autosave){
                    doAjax("metadata_batch_edit_update", changed_docs, success, null, complete);
                    document.body.style.cursor='wait';
                }
            }
        }
    });
}

/**
 * Custom renderer for handling id/value pairs on dropdowns.
 * @param instance
 * @param td
 * @param row
 * @param col
 * @param prop
 * @param value
 * @param cellProperties
 * @returns {*}
 */
function customDropdownRenderer(instance, td, row, col, prop, value, cellProperties) {
    var selectedId;
    var optionsList = cellProperties.chosenOptions.data;

    if(typeof optionsList === "undefined" || typeof optionsList.length === "undefined" || !optionsList.length) {
        Handsontable.TextCell.renderer(instance, td, row, col, prop, value, cellProperties);
        return td;
    }

    var values = (value + "").split(",");
    value = [];
    for (var index = 0; index < optionsList.length; index++) {

        if (values.indexOf(optionsList[index].id + "") > -1) {
            selectedId = optionsList[index].id;
            value.push(optionsList[index].label);
        }
    }
    value = value.join(", ");

    Handsontable.TextCell.renderer(instance, td, row, col, prop, value, cellProperties);
    return td;
}