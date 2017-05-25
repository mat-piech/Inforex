<div class="container-fluid admin_tables">
    <div class="row">
        <div class="col-md-4 tableContainer" id = "annotationSetsContainer" style="padding: 0">
            <div class="panel panel-primary scrollingWrapper" style="margin: 5px;">
                <div class="panel-heading">Annotation sets</div>
                <div class="tableContent panel-body scrolling" style="">
                    <table class="table table-striped" id="annotationSetsTable" cellspacing="1">
                        <thead>
                        <tr>
                            <th style="width: 10%" class="td-right">Id</th>
                            <th>Name</th>
                            <th class="td-center">Owner</th>
                            <th class="td-center">Access</th>
                        </tr>
                        </thead>
                        <tbody>
                        {foreach from=$annotationSets item=set}
                            <tr visibility = "{$set.public}">
                                <td class="column_id td-right">{$set.id}</td>
                                <td>{$set.description}</td>
                                <td class="td-center">{$set.screename}</td>
                                <td class="td-center">{if $set.public == 1} public {else} private {/if}</td>
                            </tr>
                        {/foreach}
                        </tbody>
                    </table>
                </div>
                <div class="panel-footer" element="annotation_set" >
                    <button type = "button" class = "btn btn-primary create create_annotation_set" data-toggle="modal" data-target="#annotation_set_modal">Create</button>
                    <button style = "display: none;" type = "button" class = "btn btn-primary edit edit_annotation_set" data-toggle="modal" data-target="#annotation_set_modal">Edit</button>
                    <button style = "display: none;" type = "button" class = "btn btn-danger deleteAnnotations ">Delete</button>
                </div>
            </div>
        </div>
        <div class="col-md-3" style="padding: 0">
            <div class="panel panel-primary tableContainer scrollingWrapper" id="annotationSubsetsContainer" style="margin: 5px; visibility: hidden;">
                <div class="panel-heading">Annotation subsets</div>
                <div class="panel-body">
                    <div class="tableContent scrolling">
                        <table id="annotationSubsetsTable" class="table table-striped" cellspacing="1">
                            <thead>
                            <tr>
                                <th>id</th>
                                <th>name</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="panel-footer" element="annotation_subset" parent="annotationSetsContainer">
                    <button type = "button" class = "btn btn-primary create adminPanelButton create_annotation_subset" data-toggle="modal" data-target="#annotation_subset_modal">Create</button>
                    <button style = "display: none;" type = "button" class = "btn btn-primary edit adminPanelButton edit_annotation_subset" data-toggle="modal" data-target="#annotation_subset_modal">Edit</button>
                    <button style = "display: none;" type = "button" class = "btn btn-danger deleteAnnotations adminPanelButton">Delete</button>
                </div>
            </div>
        </div>
        <div class="col-md-5" style="padding: 0">
            <div class="panel panel-primary tableContainer scrollingWrapper" id="annotationTypesContainer" style="margin: 5px; visibility: hidden;">
                <div class="panel-heading">Categories</div>
                <div class="panel-body">
                    <div class="tableContent scrolling">
                        <table id="annotationTypesTable" class="table table-striped" cellspacing="1">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th title="short description" style="width: 100px">Short name</th>
                                <th>Description</th>
                                <th>Default visibility</th>
                                <th style="display:none">Style</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="panel-footer" element="annotation_type" parent="annotationSubsetsContainer">
                    <button type = "button" class = "btn btn-primary create adminPanelButton create_annotation_type" data-toggle="modal" data-target="#annotation_type_modal">Create</button>
                    <button style = "display: none;" type = "button" class = "btn btn-primary edit adminPanelButton edit_annotation_type" data-toggle="modal" data-target="#annotation_type_modal">Edit</button>
                    <button style = "display: none;" type = "button" class = "btn btn-danger deleteAnnotations adminPanelButton">Delete</button>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade settingsModal" id="annotation_set_modal" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title" id = "annotation_set_header">Create annotation set</h4>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <label for="annotation_set_desc">Description:</label>
                            <textarea class="form-control" rows="5" id="annotation_set_desc"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="setAccess">Access:</label>
                            <select id="setAccess" class = "form-control">
                                <option value = "public">Public</option>
                                <option value = "private">Private</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary confirm_annotation_set" data-dismiss="modal">Confirm</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade settingsModal" id="annotation_subset_modal" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title" id = "annotation_subset_header">Create annotation subset</h4>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <label for="annotation_subset_desc">Description:</label>
                            <textarea class="form-control" rows="5" id="annotation_subset_desc"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary confirm_annotation_subset" data-dismiss="modal">Confirm</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade settingsModal" id="annotation_type_modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title" id = "annotation_type_header">Create annotation type</h4>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <label for="annotation_type_name">Name:</label>
                            <div id = "annotation_type_name_container">
                                <input class="form-control" type = "text" id="annotation_type_name">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="annotation_type_short">Short:</label>
                            <input class="form-control" type = "text" id="annotation_type_short">
                        </div>
                        <div class="form-group">
                            <label for="annotation_type_desc">Description:</label>
                            <textarea class="form-control" rows="5" id="annotation_type_desc"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="elementVisibility">Visibility:</label>
                            <select id="elementVisibility" class = "form-control">
                                <option value = "Hidden">Hidden</option>
                                <option value = "Visible">Visibile</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="annotation_type_css">Css:</label>
                            <textarea class="form-control" rows="5" id="annotation_type_css"></textarea>
                        </div>
                        <div id = "annotation_type_sample">
                            <label for="annotation_type_preview">Preview:</label>
                            <p id="annotation_type_preview">Sample</p>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary confirm_annotation_type" data-dismiss="modal">Confirm</button>
                </div>
            </div>
        </div>
    </div>