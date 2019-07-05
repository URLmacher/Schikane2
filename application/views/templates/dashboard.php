<div id="dashboard" class="hide">

    <div id="user-profile">
        <h3>Profil</h3>
        <p>Username: Heini</p>
        <p>Alter: 32</p>
        <p>Geschlecht: M</p>
    </div>

    <div id="user-friends">
        <h3>Freunde</h3>
        <p>Seppo</p>
        <p>Hansi</p>
        <p>Elvira</p>
        <p>Töfte</p>
    </div>

    <div id="user-messages">
        <h3>Nachrichten</h3>
        <table class="table" id="msg-table">
            <thead>
                <tr>
                <th >Absender</th>
                <th >Datum</th>
                <th >Betreff</th>
                <th >Nachricht</th>
                <th >Gelesen</th>
                </tr>
            </thead>
            <tbody id="msg-table-body">
                <tr>
                <td>-</td>
                <td>-</td>
                <td>-</td>
                <td>-</td>
                <td>-</td>
                </tr>
            </tbody>
        </table>
        <div class="card hide" id="single-msg">
            <div class="card-header flex-justify">
                <h3 id="msg-title"></h3>
                <h3 id="msg-sender"></h3>
            </div>
            <div class="card-body" id="msg-body">
            </div>
            <div class="btn-box flex-justify">
                <a href="#" class="btn btn-primary antworten">Antworten</a>
                <a href="#" class="btn btn-secondary back-to-table">Zurück</a>
            </div>
        </div>

        <div id="answer-form" class="hide">
            <form>
                
                <fieldset>
                
                    <div class="form-group">
                        <label for="answer-title">Empfänger</label>
                        <input value=""  type="text" class="form-control" id="send-msg-recipient"  placeholder="Empfänger">
                        <div class="error-box" id="recipient-error"></div>
                    </div>

                    <div class="form-group">
                        <label for="answer-title">Titel</label>
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

    </div>

    <div id="user-stats">
        <h3>Statistik</h3>
        <p>Spiele gewonnen: 2</p>
        <p>Spiele verloren: 5</p>
    </div>

</div>