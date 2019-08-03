<h3 class="dashboard__element__title"><?= $this->session->userdata('user_name') ?>'s Freunde</h3>
<div class="profile-friends">
    <div class="profile-friends__add-friend"></div>
    <table class="table" id="friend-table">
        <thead>
            <tr>
            <th class="table__header">Freund</th>
            <th class="table__header table__data--small">Online</th>
            <th class="table__header table__data--small">Nachricht</th>
            <th class="table__header table__data--small">Entfernen</th>
            </tr>
        </thead>
        <tbody id="friend-table-body">
        </tbody>
    </table>
</div>

