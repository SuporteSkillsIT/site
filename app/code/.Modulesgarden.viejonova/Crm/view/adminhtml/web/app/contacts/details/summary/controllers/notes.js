/***************************************************************************************
 *
 * 
 *                  ██████╗██████╗ ███╗   ███╗         Customer
 *                 ██╔════╝██╔══██╗████╗ ████║         Relations
 *                 ██║     ██████╔╝██╔████╔██║         Manager
 *                 ██║     ██╔══██╗██║╚██╔╝██║
 *                 ╚██████╗██║  ██║██║ ╚═╝ ██║         For Magento
 *                  ╚═════╝╚═╝  ╚═╝╚═╝     ╚═╝
 * 
 *    
 * @author      Piotr Sarzyński <piotr.sa@modulesgarden.com> / < >
 *              
 *                           
 * @link        http://www.docs.modulesgarden.com/CRM_For_WHMCS for documenation
 * @link        http://modulesgarden.com ModulesGarden
 *              Top Quality Custom Software Development
 * @copyright   Copyright (c) ModulesGarden, INBS Group Brand, 
 *              All Rights Reserved (http://modulesgarden.com)
 * 
 * This software is furnished under a license and mxay be used and copied only  in  
 * accordance  with  the  terms  of such  license and with the inclusion of the above 
 * copyright notice.  This software  or any other copies thereof may not be provided 
 * or otherwise made available to any other person.  No title to and  ownership of 
 * the  software is hereby transferred.
 *
 **************************************************************************************/

angular.module("mgCRMapp").controller(
        'detailsSummaryNotesCtrl',
        ['$scope', '$state', '$stateParams', 'notesService', 'blockUI', 'ngDialog',
function( $scope, $state, $stateParams, notesService, blockUI, ngDialog)
{
    /////////////////////////////
    //    
    // INITIALIZE CONTAINERS ETC    
    /////////////////////////////
    // container for notes
    $scope.notes        = [];
    $scope.showHidden   = false;
    // limit
    $scope.limitNotesTo = 5;
    // just for messages
    $scope.scopeMessages    = [];
        
        
    // Get the reference to the block service.
    $scope.notesContainer = blockUI.instances.get('lastNotesContainer');
        
    
    // just function to obtain permisions roles
    getNotes = function()
    {
        // Start blocking the table
        $scope.notesContainer.start();
        
        if($scope.showHidden === true) {
            // obtain roles from backend
            $scope.notes = notesService.getWithDeleted({resourceID: $stateParams.id, limit: $scope.limitNotesTo});
        } else {
            // obtain roles from backend
            $scope.notes = notesService.get({resourceID: $stateParams.id, limit: $scope.limitNotesTo});
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
                    
                    $scope.scopeMessages.push({
                        type: 'success',
                        title: "Success!",
                        content: 'Note has been deleted',
                    });
                
                } else {
                    $scope.notes[index].updated_at = response.updated_at;
                    $scope.notes[index].deleted_at = response.deleted_at ? response.deleted_at : null;
                    
                    $scope.scopeMessages.push({
                        type: 'success',
                        title: "Success!",
                        content: 'Note has been hidden',
                    });
                }


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

            $scope.scopeMessages.push({
                type: 'success',
                title: "Success!",
                content: 'Note has been restored',
            });

        },function(response) {
            $scope.scopeMessages.push({
                type: 'danger',
                title: "Error!",
                content: response.data.msg ? response.data.msg : response.statusText,
            });

        });
    };
    
    $scope.$on('summaryNotesTriggerRefresh', function(event) {
        getNotes();
    });
    
    $scope.forceReloadNotes = function() {
        getNotes();
    };
    
    
}]);