{*
 * Part of the Inforex project
 * Copyright (C) 2013 Michał Marcińczuk, Jan Kocoń, Marcin Ptak
 * Wrocław University of Technology
 * See LICENCE 
 *}

{include file="inc_header2.tpl"}
{include file="inc_administration_top.tpl"}

<div class="container-fluid admin_tables">
    <div class="row">
        <div class="col-md-4 tableContainer" id = "relationSetsContainer" style="padding: 0">
                <div class="panel panel-primary scrollingWrapper" style="margin: 5px;">
                    <div class="panel-heading">Relation sets</div>
                    <div class="tableContent panel-body scrolling" style="">
                        <table class="tablesorter table table-striped" id="relationSetsTable" cellspacing="1">
                            <thead>
                            <tr>
                                <th>id</th>
                                <th>description</th>
                            </tr>
                            </thead>
                            <tbody>
                            {foreach from=$relationSets item=set}
                                <tr>
                                    <td class = "column_id">{$set.id}</td>
                                    <td>{$set.description}</td>
                                </tr>
                            {/foreach}
                            </tbody>
                        </table>
                    </div>
                </div>
        </div>
        <div class="col-md-4" style="padding: 0">
            <div class="panel panel-primary scrollingWrapper tableContainer" id="relationTypesContainer" style="margin: 5px; display: none;">
                <div class="panel-heading">Relation types</div>
                <div class="panel-body">
                    <div class="tableContent scrolling">
                        <table id="relationTypesTable" class="tablesorter table table-striped" cellspacing="1">
                            <thead>
                            <tr>
                                <th>id</th>
                                <th>name</th>
                                <th>description</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="panel-footer" element="relation_type" parent="relationSetsContainer">
                    <button type = "button" class = "btn btn-primary create adminPanelButton createRelation" data-toggle="modal" data-target="#create_relation_modal">Create</button>
                    <button style = "display: none;" type = "button" class = "btn btn-primary edit adminPanelButton editRelation" data-toggle="modal" data-target="#edit_relation_modal">Edit</button>
                    <button style = "display: none;" type = "button" class = "btn btn-danger delete adminPanelButton">Delete</button>
                </div>
            </div>
        </div>
        <div class="col-md-4" style="padding: 0">
            <div class="panel panel-primary scrollingWrapper tableContainer" id="relationGroupsContainer" style="margin: 5px; display: none;">
                <div class="panel-heading">Relation groups</div>
                <div class="panel-body">
                    <div class="tableContent scrolling">
                        <table id="relationGroupsTable" class="tablesorter table table-striped" cellspacing="1">
                            <thead>
                            <tr>
                                <th>direction</th>
                                <th>annotation</th>
                            </tr>
                            </thead>
                            <tbody id = "relations_groups_content">
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="panel-footer" element="relation_type" parent="relationTypesContainer">
                    <button type = "button" class = "btn btn-primary create adminPanelButton createRelationGroup" data-toggle="modal" data-target="#create_relation_group_modal">Edit relation groups</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade settingsModal" id="create_relation_modal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Create relation type</h4>
            </div>
            <div class="modal-body">
                <form id = "create_relation_form">
                    <div class="form-group">
                        <label for="create_relation_name">Name: <span class = "required_field">*</span></label>
                        <input class="form-control" name = "create_relation_name" id="create_relation_name">
                    </div>
                    <div class="form-group">
                        <label for="create_relation_description">Description: </label>
                        <textarea class="form-control" name = "create_relation_description" rows="5" id="create_relation_description"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary confirm_relation_create">Confirm</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade settingsModal" id="edit_relation_modal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Edit relation type</h4>
            </div>
            <div class="modal-body">
                <form id = "edit_relation_form">value.description = "";
                    <div class="form-group">
                        <label for="edit_relation_name">Name: <span class = "required_field">*</span></label>
                        <input class="form-control" name = "edit_relation_name" id="edit_relation_name">
                    </div>
                    <div class="form-group">
                        <label for="edit_relation_description">Description: </label>
                        <textarea class="form-control" name = "edit_relation_description" rows = "5" id="edit_relation_description"></textarea>
                    </div>
                    <div class = "form-group">
                        <div>
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>Direction</th>
                                    <th>Annotation</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody id = "relations_groups_content">
                                </tbody>
                            </table>
                        </div>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary confirm_relation_edit">Confirm</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade settingsModal" id="create_relation_group_modal" role="dialog">
    <div class="modal-dialog" style = "margin: 0 auto;">
        <div class="modal-content" style = "width: 90vw;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Create relation group</h4>
            </div>
            <div class="modal-body">
                <form id = "create_relation_group_form" style = "overflow: auto; height: 70vh;">
                    <div class = "col-lg-4">
                        <table class="relationGroupManagement table table-striped">
                            <thead>
                                <tr>
                                    <th>Annotation set</th>
                                    <th>Description</th>
                                    <th>Source</th>
                                    <th>Target</th>
                                </tr>
                            </thead>
                            <tbody id = "relation_group_annotation_set">
                            </tbody>
                        </table>
                    </div>
                    <div class = "col-lg-4">
                        <table class="relationGroupManagement table table-striped">
                            <thead>
                            <tr>
                                <th>Annotation subset</th>
                                <th>Description</th>
                                <th>Source</th>
                                <th>Target</th>
                            </tr>
                            </thead>
                            <tbody id = "relation_group_annotation_subset">
                            </tbody>
                        </table>
                    </div>
                    <div class = "col-lg-4">
                        <table class="relationGroupManagement table table-striped">
                            <thead>
                            <tr>
                                <th>Annotation type</th>
                                <th>Description</th>
                                <th>Source</th>
                                <th>Target</th>
                            </tr>
                            </thead>
                            <tbody id = "relation_group_annotation_type">
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{include file="inc_footer.tpl"}
