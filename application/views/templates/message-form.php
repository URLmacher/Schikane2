
    <div id="send-msg-form-wrapper" class="msg__wrapper hide" >
        <form  id="send-msg-form" class="msg__form" >

            <div class="send-msg-close msg__close-btn"></div>

            <fieldset class="msg__fieldset">
                <h3 id="send-msg-legend" class="msg__title primary-text">Neue Nachricht</h3>

                <div class="msg__form-group">
                    <label for="send-msg-recipient">Empfänger</label>
                    <input value=""  type="text"  id="send-msg-recipient"  placeholder="Empfänger">
                    <div class="error-box" id="recipient-error"></div>
                </div>

                <div class="msg__form-group">
                    <label for="send-msg-title">Titel</label>
                    <input value="" type="text"  id="send-msg-title"  placeholder="Hier den Titel reinschreiben">
                    <div class="error-box" id="title-error"></div>
                </div>
            
                <div class="msg__form-group">
                    <label for="send-msg-body">Text</label>
                    <textarea   id="send-msg-body" rows="8"></textarea>
                    <div class="error-box" id="body-error"></div>
                </div>

                <div class="msg__form-group">
                    <button type="submit" id="send-msg-send" class="btn msg__btn btn-primary">Abschicken</button>
                </div>
            </fieldset>

        </form>
    </div>  