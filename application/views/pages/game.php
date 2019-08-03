<input id="playerUsername" type="hidden" value="<?= $this->session->userdata('user_name') ?>">
<div class="wrapper">
  <div class="sub-wrapper">

    <div class="ablage-wrapper" id="p2Ablage">
      <div class="p2Ablage ablage" id="p2Ablage|0"></div>
      <div class="p2Ablage ablage" id="p2Ablage|1"></div>
      <div class="p2Ablage ablage" id="p2Ablage|2"></div>
      <div class="p2Ablage ablage" id="p2Ablage|3"></div>
      <div class="p2Joker ablage" id="p2Jokerablage|0"></div>
    </div>

    <div class="utility">
      <div class="draw-stack draw-stack-p2" id="p2Drawstack|0"></div>
      <div class="utility__center--wrapper">
        
        <div id="p2Display"  class="display">
          <div id="p2Points" class="display__points">10</div>
          <div id="p2Name" class="display__name">Hugo</div>
        </div>
        <div id="mainstack" data-mainstack="0">
          <div id="mainstack-dummy"></div>
        </div>
        <div id="p1Display" class="display">
          <div id="p1Points" class="display__points">12</div>
          <div id="p1Name" class="display__name">Rudolfo</div>
        </div>
        
      </div>
      <div class="draw-stack draw-stack-p1" id="p1Drawstack|0" data-drawstack="1"></div>
    </div>

    <div class="ablage-wrapper" id="p1Ablage" data-ablage="1">
      <div class="p1Ablage ablage" data-ablageid="0" id="p1Ablage|0"></div>
      <div class="p1Ablage ablage" data-ablageid="1" id="p1Ablage|1"></div>
      <div class="p1Ablage ablage" data-ablageid="2" id="p1Ablage|2"></div>
      <div class="p1Ablage ablage" data-ablageid="3" id="p1Ablage|3"></div>
      <div class="p1Joker ablage" data-jokerablage="1"id="p1Jokerablage|0"></div>
    </div>
  </div>

  <div id="play-area">
    <div class="play-area__stack" data-playareaid="0" id="playarea|0"></div>
    <div class="play-area__stack" data-playareaid="1" id="playarea|1"></div>
    <div class="play-area__stack" data-playareaid="2" id="playarea|2"></div>
    <div class="play-area__stack" data-playareaid="3" id="playarea|3"></div>
    <div class="play-area__stack" data-playareaid="4" id="playarea|4"></div>
    <div class="play-area__stack" data-playareaid="5" id="playarea|5"></div>
    <div class="play-area__stack" data-playareaid="6" id="playarea|6"></div>
    <div class="play-area__stack" data-playareaid="7" id="playarea|7"></div>
  </div>
</div>
<div id="p1Hand|0" class="hand" data-hand="0"></div>


<script src="<?php echo base_url(); ?>assets/js/game.js"></script> 
