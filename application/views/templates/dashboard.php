<div id="dashboard" class="dashboard hide">

    <div id="user-profile" class="dashboard__element">
       <?php
        $this->load->view('dashboard/profile-view');
        ?>
    </div>

    <div id="user-friends" class="dashboard__element">
        <?php
        $this->load->view('dashboard/friends-view');
        ?>
    </div>

    <div id="user-messages" class="dashboard__element">   
        <?php
        $this->load->view('dashboard/message-view');
        ?>
    </div>

    <div id="user-stats" class="dashboard__element">
        <?php
        $this->load->view('dashboard/stats-view');
        ?>
    </div>

</div>