<div id="dashboard" class="hide">

    <div id="user-profile">
       <?php
        $this->load->view('dashboard/profile-view');
        ?>
    </div>

    <div id="user-friends">
        <?php
        $this->load->view('dashboard/friends-view');
        ?>
    </div>

    <div id="user-messages">   
        <?php
        $this->load->view('dashboard/message-view');
        ?>
    </div>

    <div id="user-stats">
        <?php
        $this->load->view('dashboard/stats-view');
        ?>
    </div>

</div>