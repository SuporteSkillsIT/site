/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */



angular.module("mgCRMapp").controller(
        'leadNotesController',
        ['$scope', '$state', '$stateParams', 'notesService', 'blockUI', 'ngDialog',
function( $scope, $state, $stateParams, notesService, blockUI, ngDialog)
{
    /////////////////////////////
    //    
    // INITIALIZE CONTAINERS ETC    
    /////////////////////////////
    // container for new note object
    $scope.newNoteContent = null;
    // show/hide new note form :D
    $scope.newNoteVisible = true;
    // determinate if form means add note or edit (if false)
    $scope.formModeAdd = true;
    $scope.currentlyEditedID = null;
    // container for notes
    $scope.notes = [];
    $scope.showHidden = false;
    // messages 
    $scope.scopeMessages = [];
    
    /**
     * Submit new note to backend
     */
    $scope.submitNoteForm = function()
    {
        // push loading indicator
        $scope.$emit('loadingNotification', {type: 'progress'});
        $scope.newFormWorking = true;
        
        // send query
        res = notesService.addNew($stateParams.id, $scope.newNoteContent).then(function(response) 
        {
            // plain update container with added note
            $scope.notes.push(response.data.new);
            
            // cleare form
            $scope.newNoteContent = null;
            
            // loading indicator as compleate
            $scope.$emit('loadingNotification', {type: 'finished'});
            $scope.newFormWorking = false;
        }, function(response) {
            console.log(response);
            // push message to editable (that module handle show this error in form)
            return response.data.msg ? response.data.msg : 'error occured';
        });
    };
        
        
    // Get the reference to the block service.
    $scope.notesContainer = blockUI.instances.get('notesContainer');
        
    
    // just function to obtain permisions roles
    getNotes = function()
    {
        // Start blocking the table
        $scope.notesContainer.start();
        
        if($scope.showHidden === true) {
            // obtain roles from backend
            $scope.notes = notesService.getWithDeleted({resourceID: $stateParams.id});
        } else {
            // obtain roles from backend
            $scope.notes = notesService.get({resourceID: $stateParams.id});
        }
        
        // when we recieve it
        $scope.notes.$promise.then(function(data) {
            $scope.notes = data;
            // on init get that roles
            $scope.notesContainer.stop();
        }, function(error) {
            console.log(error);
            // on init get that roles
            $scope.notesContainer.stop();
        });
    };
    // trigger on init
    getNotes();
    
    // turn off/on display hidden records and obtain it from backend again
    $scope.triggerShowHidden = function() {
        $scope.showHidden = !$scope.showHidden;
        getNotes();
    };
    
    /**
     * helper might be usefull
     */
    function getNoteByID(noteID)
    {
        
        for (var i = 0; i < $scope.notes.length; i++) 
        {
            if( noteID == $scope.notes[i].id ) {
                return $scope.notes[i];
            }
        }   
        
        return false;
    };
    
    
    
    /////////////////////////////
    // DELETE
    /////////////////////////////
    $scope.triggerDeleteNote = function(noteID, force)
    {
        if(force === true) {
            msg = 'Are you sure you want to delete this note ?';
            msgok = 'Note has been deleted';
        } else {
            msg = 'Are you sure you want to hide this note ?';
            msgok = 'Note has been hidden';
        }
        
        // triger confirm dialog
        $scope.confirmDeleteDialog = ngDialog.openConfirm({
            template:'\
                <h2>'+msg+'</h2>\
                <div class="ngdialog-buttons text-center">\
                    <button type="button" class="ngdialog-button ngdialog-button-secondary" ng-click="closeThisDialog(0)">No</button>\
                    <button type="button" class="ngdialog-button ngdialog-button-primary" ng-click="confirm(1)">Yes</button>\
                </div>',
            plain: true,
            className: 'ngdialog-theme-default mg-wrapper ngdialog-overlay',
            overlay: false
            
        }).then(function(){
            // push loading indicator
            $scope.$emit('loadingNotification', {type: 'progress'});

            // send query
            note = getNoteByID(noteID);
            
            if(force === true) {
                res = notesService.forceDelete({id: note.id, resource_id:note.resource_id});
            } else {
                res = notesService.softDelete({id: note.id, resource_id:note.resource_id});
            }
        
            res.$promise.then(function(response) {
                // loading indicator as compleate
                $scope.$emit('loadingNotification', {type: 'finished'});
            
                var index = $scope.notes.indexOf(note);
                
                if(force === true) {
                    $scope.notes.splice(index, 1);     
                } else {
                    $scope.notes[index].updated_at = response.updated_at;
                    $scope.notes[index].deleted_at = response.deleted_at ? response.deleted_at : null;
                }
                
                $scope.scopeMessages.push({
                    type: 'success',
                    title: "Success!",
                    content: msgok,
                });


            },function(response) {
                $scope.scopeMessages.push({
                    type: 'danger',
                    title: "Error!",
                    content: response.data.msg ? response.data.msg : response.statusText,
                });

            });
    
        });
    }
    
    /**
     * Restore hidden note
     */
    $scope.triggerRestoreNote = function(noteID)
    {
        // push loading indicator
        $scope.$emit('loadingNotification', {type: 'progress'});

        // send query
        note = getNoteByID(noteID);
        
        res = notesService.restore(note);

        res.$promise.then(function(response) {
            // loading indicator as compleate
            $scope.$emit('loadingNotification', {type: 'finished'});
            var index = $scope.notes.indexOf(note);
            
            $scope.notes[index].updated_at = response.updated_at;
            $scope.notes[index].deleted_at = null;


        },function(response) {
            $scope.scopeMessages.push({
                type: 'danger',
                title: "Error!",
                content: response.data.msg ? response.data.msg : response.statusText,
            });

        });
    };
    
    
    /**
     * Trigger edit
     */
    $scope.triggerEditNote = function(noteID)
    {
        $scope.formModeAdd       = false;
        $scope.currentlyEditedID = noteID;
        
        note = getNoteByID($scope.currentlyEditedID);
        $scope.newNoteContent = note.content;
    };
    
    /**
     * Save edited note to backend
     */
    $scope.submitEditedForm = function()
    {
        // push loading indicator
        $scope.$emit('loadingNotification', {type: 'progress'});
        $scope.newFormWorking = true;
        
        note = getNoteByID($scope.currentlyEditedID);
        note.content = $scope.newNoteContent;
        
        // send query
        res = notesService.update(note).$promise.then(function(response) 
        {
            var index = $scope.notes.indexOf(note);
            $scope.notes[index] = response.note;
            
            // cleare form
            $scope.cancelEditForm();
            
            // loading indicator as compleate
            $scope.$emit('loadingNotification', {type: 'finished'});
            $scope.newFormWorking = false;
            $scope.scopeMessages.push({
                type: 'success',
                title: "Success!",
                content: 'Note has been edited',
            });

        }, function(response) {
            console.log(response);
            // push message to editable (that module handle show this error in form)
            return response.data.msg ? response.data.msg : 'error occured';
        });
    };
    
    /**
     * Cancel edit form
     */
    $scope.cancelEditForm = function()
    {
        $scope.currentlyEditedID       = null; 
        $scope.formModeAdd             = true;
        $scope.newNoteContent   = null;
    };
    
    $scope.textAngularOptions = {}
}]);