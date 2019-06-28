<div class='container'>
  <div class="jumbotron">
    <h1 class="display-3"><?=$title ?></h1>
  </div>
  <?php echo validation_errors(); ?>
  <section class='register'>
    <?php echo form_open_multipart('users/register'); ?>
      <fieldset>

        <div class="form-group">
          <label for="username">Username</label>
          <input id='username' name='user_name' type="text" class="form-control"  placeholder='Username'>
        </div>

        <div class="form-group">
          <label for="password">Passwort</label>
          <input id='password' name='password' type="password" class="form-control" >
        </div>

        <div class="form-group">
          <label for="password2">Passwort wiederholen</label>
          <input id='password2' name='password2' type="password" class="form-control" >
        </div>

        <div class="form-group">
          <button type="submit" class="btn btn-primary">Submitieren</button>
        </div>
      
      </fieldset>
    </form>
  </section>
</div>