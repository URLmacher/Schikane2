<div class="kastel2">
  <h1 class="kastel2__title"><?=$title ?></h1>
  <section class='kastel2__content'>
    <?php if(validation_errors()) {
      echo  '<div class="kastel2__error-box">';
      echo validation_errors('<p class="error">', '</p>'); 
      echo '</div>';
    }?>
    <?php echo form_open_multipart('users/register'); ?>
      <fieldset>

        <div class="form-group kastel2__form-group">
          <label for="username">Username</label>
          <input id='username' name='user_name' type="text" class="form-control"  placeholder='Username'>
        </div>

        <div class="form-group kastel2__form-group">
          <label for="password">Passwort</label>
          <input id='password' name='password' type="password" class="form-control" autocomplete="new_password">
        </div>

        <div class="form-group kastel2__form-group">
          <label for="password2">Passwort wiederholen</label>
          <input id='password2' name='password2' type="password" class="form-control" autocomplete="new_password">
        </div>

        <div class="kastel2__btn-box">
          <button type="submit" class="btn ">Submitieren</button>
        </div>
      
      </fieldset>
    </form>
  </section>
</div>