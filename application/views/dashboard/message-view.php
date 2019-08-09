<h3 class="dashboard__element__title"><?= $this->session->userdata('user_name') ?>'s Nachrichten</h3>
<div class="profile-msgs">
    <div class="profile-msgs__new-msg" id="profile-msgs__new-msg"></div> 
    <table class="table" id="msg-table">
        <thead>
            <tr>
            <th class="table__header">Absender</th>
            <th class="table__header table__data--small">Datum</th>
            <th class="table__header">Betreff</th>
            <th class="table__header">Nachricht</th>
            <th class="table__header table__data--small">Gelesen</th>
            </tr>
        </thead>
        <tbody id="msg-table-body">
            <tr>
            <td class="table__data">-</td>
            <td class="table__data">-</td>
            <td class="table__data">-</td>
            <td class="table__data">-</td>
            <td class="table__data">-</td>
            </tr>
        </tbody>
    </table>
    <div class="profile-msgs__single-msg hide" id="single-msg">
        <div class="profile-msgs__single-msg__head">
            <h3 class="primary-text" id="msg-title"></h3>
            <h3 class="secondary-text" id="msg-sender"></h3>
        </div>
        <div class="profile-msgs__single-msg__body" id="msg-body">
            </div>
            <div class="btn-box">
                <a href="#" class="btn  antworten">Antworten</a>
                <div id="specialpurpose"></div>
            <a href="#" class="btn  back-to-table">Zurück</a>
        </div>
    </div>
</div>

