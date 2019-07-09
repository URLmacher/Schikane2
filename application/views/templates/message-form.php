
    <div id="send-msg-form-wrapper" class="hide" >
        <form  id="send-msg-form" >
            <div class="send-msg-close stand-in-btn">X</div>
            <fieldset>
            <legend id="send-msg-legend">Neue Nachricht</legend>
                <div class="form-group">
                    <label for="send-msg-recipient">Empfänger</label>
                    <input value=""  type="text" class="form-control" id="send-msg-recipient"  placeholder="Empfänger">
                    <div class="error-box" id="recipient-error"></div>
                </div>

                <div class="form-group">
                    <label for="send-msg-title">Titel</label>
                    <input value="" type="text" class="form-control" id="send-msg-title"  placeholder="Hier den Titel reinschreiben">
                    <div class="error-box" id="title-error"></div>
                </div>
            
                <div class="form-group">
                    <label for="send-msg-body">Text</label>
                    <textarea  class="form-control" id="send-msg-body" rows="3"></textarea>
                    <div class="error-box" id="body-error"></div>
                </div>
            
            <button type="submit" id="send-msg-send" class="btn btn-primary">Abschicken</button>
                
            </fieldset>

        </form>
    </div>  