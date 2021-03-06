/**
 * -- Form Submit --
 * Handles form submission triggered by the element placed on.
 * It will only execute if there is no other submission
 * in progress. Behavior is to locate the form for submission,
 * serialize form fields (including files), execute submit transformation
 * function on form fields if present, and send the result to
 * Service.ServerRequest to be processed.
 */
Controller.AddProperty("FormSubmit",function(elem){
    //exit if another submission is in progress
    if(Service.ActionLoading) return false;
    Service.ActionButton = jQuery(elem);
    Service.ActionLoading = true;
    Service.CanSubmitForm = true;
    let target = Service.ActionButton.data(Service.SYSTEM_ACTION);
    let custom = Service.ActionButton.data(Service.SYSTEM_CUSTOM);
    let complete = Service.ActionButton.data(Service.SYSTEM_COMPLETE);
    let pre = Service.ActionButton.data(Service.SYSTEM_PRE_FORM_EXECUTION);
    let requestHeaders = Service.ActionButton.data(Service.SYSTEM_HEADERS);
    let site_url = "";
    let method = "";
    let params = new FormData();
    let headers = [];
    let data = [];

    //load the form from target specified. if not
    //load the parent form element
    if(typeof target === "undefined"){
       target = "";
        Service.LoadedForm = jQuery(Service.ActionButton.parents('form'));
    }
    else{
        if (target.substring(0, 1) !== "#") target = `#${target}`;
        target = jQuery(document).find(target);
        let form = target.find("form");
        if (form.length > 0) {
            Service.LoadedForm = form;
        } else {
            form = jQuery("<form></form>",
                {
                    action: Service.ActionButton.data(Service.SYSTEM_URL),
                    method: Service.ActionButton.data(Service.SYSTEM_METHOD)
                });
            form.append(target);
            Service.LoadedForm = form;
        }
    }

    //if a complete function is defined add it to the loaded form
    if(typeof complete !== "undefined"){
        Service.LoadedForm.data(Service.SYSTEM_COMPLETE,Service.ActionButton.data(Service.SYSTEM_COMPLETE));
    }

    //if headers function is defined execute it and get headers for request
    if(typeof requestHeaders !== "undefined"){
        if(Controller.hasOwnProperty(requestHeaders)){
            headers = Controller[requestHeaders]();
        }
        else if(Service.hasOwnProperty(requestHeaders)){
            headers = Service[requestHeaders]();
        }
    }

    // if LoadedForm is not specified, terminate the action
    if(Service.LoadedForm.length <= 0){
        Service.ActionButton = null;
        return false;
    }

    //retrieve form fields and submission data from loaded form
    //exclude file input from search...handled separately below
    data = jQuery(Service.LoadedForm.serializeArray());
    jQuery.each(data,function(){
        params.append(this.name,this.value);
    });
    site_url = Service.LoadedForm[0].action;
    method = Service.LoadedForm[0].method;

    if(typeof site_url === "undefined") return false;
    if(typeof method === "undefined") method = "POST";
    method = method.toUpperCase();

    //determine if the form has a file, and if so serialize
    //using FormData object, or just use an object
    const file = Service.LoadedForm.find("input[type='file']");
    if(file.length > 0){
        jQuery(file).each(function(e){
            //determine if multiple files are selected and add
            //each of them to the payload
            if(file[e].files.length > 1){
                let fileName = file[e].name;
                //determine if file name is array or not
                if(fileName.substring(fileName.length -1,fileName.length) !== "]"){
                    fileName = `${file[e].name}[]`;
                }
                for(let i=0; i<file[e].files.length; i++){
                    params.append(fileName,file[e].files[i]);
                }
            }
            else{
                params.append(file[e].name,file[e].files[0]);
            }
        });
    }

    //determine if there are pre execution events
    if(typeof pre !== "undefined"){
        pre = pre.split("|");
        pre.forEach(function(item){
            if(Controller.hasOwnProperty(item)){
                Controller[item](params);
            }
            else if(Service.hasOwnProperty(item)){
                Service[item](params);
            }
        });
    }

    //determine if there are submit transformations and execute them
    if(typeof custom !== "undefined")
        params = Service.ExecuteSubmitTransformation(custom,Service.LoadedForm,params);

    //determine the response handlers to use for success and error
    //defaults are chosen by default obviously
    let success = Service.FormSubmitSuccessHandler;
    let error = Service.ErrorHandler;
    let successHandler = Service.ActionButton.data(Service.SYSTEM_SUCCESS_HANDLER);
    let errorHandler = Service.ActionButton.data(Service.SYSTEM_ERROR_HANDLER);
    if(typeof successHandler !== "undefined" && Controller.hasOwnProperty(successHandler)){
        success = Controller[successHandler];
    }
    if(typeof errorHandler !== "undefined" && Controller.hasOwnProperty(errorHandler)){
        error = Controller[errorHandler];
    }

    if(Service.CanSubmitForm === false){
        Service.ActionButton = null;
        return false;
    }

    //determine how the form submission should be processed
    //if a specific action was specified we use that
    //otherwise we use the default server request
    let ex = Service.Action[target];
    if(typeof ex !== "undefined") ex({
        params,
        success,
        error,
        headers,
        site: site_url,
        request: method
    });
    else Service.ServerRequest({
        params,
        success,
        error,
        headers,
        site: site_url,
        request: method
    });
    Service.ActionLoading = false;
});

/**
 * -- File Select --
 * Handles file selection and preview. It will only execute if
 * there is no other action in progress. Behaviour is to
 * locate the container with the file input and image,
 * execute click function on file input to launch dialog,
 * and setup the preview for showing when selected.
 */
Controller.AddProperty("FileSelect",function(elem){
    //if there is a triggered action do not execute
    if(Service.ActionLoading) return false;
    Service.ActionButton = jQuery(elem);
    Service.ActionLoading = true;
    let target = Service.ActionButton.data(Service.SYSTEM_ACTION);
    let custom = Service.ActionButton.data(Service.SYSTEM_CUSTOM);
    //load the form associated with the file
    const form = (typeof target === "undefined")
        ? jQuery(Service.ActionButton.parents(Service.SYSTEM_FILE_UPLOAD_CONTAINER))
        : jQuery(document).find(`#${target}`);
    // find the file input element
    let input = form.find("input[type=file]");
    // find the image element that displays preview
    let img = form.find("img");
    // trigger click event that opens upload file dialog
    input.click();
    // update preview image when file is changed
    input.change(function() {
        Service.ImagePreview(input, img);
    });
    input.change();
    // execute custom code on the form
    if(typeof custom !== "undefined"){
        Service.ExecuteCustom(custom,form);
    }
    Service.ActionLoading = false;
    Service.ActionButton = null;
});

/**
 * -- Modal Select --
 * Handles launching modals. It will only execute if
 * there is no other action in progress. Behaviour is to
 * Find the element specified and update the loaded modal
 * property with it, place it on the DOM in the modal
 * container (this is just an empty div with the specified
 * class that MUST be in the layout), execute any custom
 * modifications, and launch.
 */
Controller.AddProperty("ModalSelect",function(elem){
    //if there is a triggered action do not execute
   if(Service.ModalLoading) return false;
    Service.ActionButton = jQuery(elem);
    Service.ModalLoading = true;
    //make action button aware of loaded type
    Service.ActionButton.data(Service.SYSTEM_LOAD_TYPE,"modal");
    //if a modal is already loaded do not execute
    if(Service.LoadedModal === null){
        let action = Service.ActionButton.data(Service.SYSTEM_ACTION);
        Service.LoadedModal = Service.FindElement(action);
        if(!Service.LoadedModal.hasClass("modal")){
            let action = Service.LoadedModal.data(Service.SYSTEM_ACTION);
            if(typeof action !== "string")
                action = "";
            const modalData = Service.LoadedModal.html();
            Service.LoadedModal = jQuery(
                `<div class="modal fade" role="dialog" data-action="${action}">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title"></h4>
                            </div>
                            <div class="modal-body">
                                ${modalData}
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>`
            );
        }
        //place modal on the DOM
        const modalContainer = jQuery(ModalContainer);
        modalContainer.empty().append(Service.LoadedModal);
        //update modal attributes
        const dataAttributes = Service.ActionButton.data();
        const filterList = [Service.SYSTEM_ACTION,Service.SYSTEM_CUSTOM];
        jQuery.each(dataAttributes,function(key,value){
            //filter out action and custom attribute
            // these are defined on the modal
            if(jQuery.inArray(key,filterList) === -1){
                Service.LoadedModal.data(key,value);
            }
        });
        let custom = Service.ActionButton.data(Service.SYSTEM_CUSTOM);
        //execute custom changes
        if(typeof custom !== "undefined"){
            Service.ExecuteCustom(custom,Service.LoadedModal);
        }
        // launch modal
        Service.LaunchModal();
    }
    Service.ModalLoading = false;
    Service.ActionButton = null;
});

/**
 * -- Panel Select --
 * Handles loading new panels unto the DOM. It will only
 * execute if there is no other action in progress.
 * Behaviour is to Find the element specified and update
 * the loaded panel property with it, determine the target
 * where the panel should be placed, load panel unto
 * the DOM, and execute custom modifications if specified
 */
Controller.AddProperty("PanelSelect",function(elem){
    //if there is a triggered action do not execute
    if(Service.PanelLoading) return false;
    Service.ActionButton = jQuery(elem);
    Service.PanelLoading = true;
    //make action button aware of loaded type
    Service.ActionButton.data(Service.SYSTEM_LOAD_TYPE,"panel");
    //get history url if defined
    const history = Service.ActionButton.data(Service.SYSTEM_HISTORY);
    //locate panel
    const panel = Service.FindElement(Service.ActionButton.data(Service.SYSTEM_ACTION));
    //update modal attributes
    const filterList = [Service.SYSTEM_ACTION,Service.SYSTEM_CUSTOM,Service.SYSTEM_TARGET];
    jQuery.each(Service.ActionButton.data(),function(key,value){
        //filter out action and custom attribute
        // these are defined on the panel
        if(jQuery.inArray(key,filterList) === -1){
            panel.data(key,value);
        }
    });
    //add panel trigger information to panel
    panel.data(`panel-${Service.SYSTEM_ACTION}`,Service.ActionButton.data(Service.SYSTEM_ACTION));
    panel.data(`panel-${Service.SYSTEM_CUSTOM}`,Service.ActionButton.data(Service.SYSTEM_CUSTOM));
    panel.data(`panel-${Service.SYSTEM_TARGET}`,Service.ActionButton.data(Service.SYSTEM_TARGET));
    //adding panel change to browser history
    //ignore if jistory data attribute is defined
    if(typeof history === "undefined"){
        window.history.pushState({
            data: Service.ActionButton.data(),
            panelSelect: true
        },"");
        /*window.history.pushState({
            data: Service.ActionButton.data(),
            panelSelect: true
        },"",`#/${Service.ActionButton.data(Service.SYSTEM_ACTION)}`)*/
    }
    window.document.title = `${Service.Title} - ${Service.ActionButton.data(Service.SYSTEM_ACTION)}`;
    //load panel unto the DOM
    if(typeof  Service.ActionButton.data(Service.SYSTEM_TARGET) !== "undefined"){
        Service.LoadPanel(panel,Service.ActionButton.data(Service.SYSTEM_TARGET));
    }
    else{
        Service.LoadPanel(panel);
    }
    //execute custom changes
    let custom = Service.ActionButton.data(Service.SYSTEM_CUSTOM);
    if(typeof custom !== "undefined"){
        Service.ExecuteCustom(custom,Service.LoadedPanel);
    }
    Service.PanelLoading = false;
    Service.ActionButton = null;
});

/**
 * -- Reload Panel --
 * this function reloads the current panel
 */
Controller.AddProperty("ReloadPanel",function(){
    //get panel information from current loaded panel
    const panelAction = Service.LoadedPanel.data(`panel-${Service.SYSTEM_ACTION}`);
    const panelCustom = Service.LoadedPanel.data(`panel-${Service.SYSTEM_CUSTOM}`);
    const panelTarget = Service.LoadedPanel.data(`panel-${Service.SYSTEM_TARGET}`);
    //create a action link to fire the controller action
    const link = jQuery("<a>");
    //add the data attributes to created link
    if(typeof panelAction !== "undefined") link.data(Service.SYSTEM_ACTION,panelAction);
    else return;
    if(typeof panelCustom !== "undefined") link.data(Service.SYSTEM_CUSTOM,panelCustom);
    if(typeof panelTarget !== "undefined") link.data(Service.SYSTEM_TARGET,panelTarget);
    //fire controller action with created anchor tag
    Controller.PanelSelect(link[0]);
});

window.onpopstate = function(event){
    //get page state
    const state = event.state;
    if(state !== null){
        if(state.panelSelect){
            //create a action link to fire the controller action
            const link = jQuery("<a>");
            //add the data attributes recorded in the state
            jQuery.each(state.data,function(i,e){
                link.attr(`data-${i}`,e);
            });
            //add a history data attribute so the panel
            //ignores the entry for pushState
            link.attr("data-history",true);
            //fire controller action with created anchor tag
            Controller.PanelSelect(link[0]);
        }
    }
};
