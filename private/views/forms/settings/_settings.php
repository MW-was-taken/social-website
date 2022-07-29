<?php
if (isset($_POST["submit"])) {
  $Bio = $_POST["bio"];
  $Bio = nl2br($Bio);
  $Bio = PurifyInput($Bio);
  $Bio = ToMarkdown($Bio);


  if (BioTooLong($Bio)) {
    header("location: ../../settings/?error=Bio too long!");
    exit();
  }

  if (empty($Bio)) {
    $_SESSION["error"] = "Bio is empty!";
    header("Location: /settings?test=test");
    exit();
  }

  // insert user_bio into users table
  $statement = $conn->prepare("UPDATE users SET user_bio = :bio WHERE user_id = :user_id");
  $statement->execute(array(':bio' => $Bio, ':user_id' => $_SESSION['UserID']));
  $_SESSION["success"] = "Bio updated successfully!";
  header("location: /settings/");
  exit();
}
$bio = GetBio($conn, $_SESSION["UserID"]);
$bio = preg_replace("/^(<br \/>)/", "", $bio);
$theme = GetTheme();
// get error from url
@$error = $_SESSION["error"];
// if error is set
HandleError($error);
// get error from url
@$note = $_SESSION["note"];
// if error is set
HandleNote($note);
// unset both error and note
unset($_SESSION["error"]);
unset($_SESSION["note"]);

?>
<div class="card users">
  <div class="card-header">
    Site Settings
  </div>
  <div class="card-body">
    <h4>Bio</h4>
    <form action="/settings/" method="post">
      <textarea name="bio" class="form-control" rows="3" placeholder="Tell us about you..."><?php echo $bio; ?></textarea>
      <br>
      <button type="submit" name="submit" class="btn btn-primary">Submit</button>
    </form>
    <h4>Theme</h4>
    <form action="../../config/forms/theme.php" method="post">
      <select name="theme" class="form-control">
        <option value="1" <?php if ($theme == 1) {
                            echo "selected";
                          } ?>>Classic</option>
        <option value="2" <?php if ($theme == 2) {
                            echo "selected";
                          } ?>>The Fancy Ol' Purple Theme</option>
        <option value="3" <?php if ($theme == 3) {
                            echo "selected";
                          } ?>>eifo's cool red theme</option>
        <option value="4" <?php if ($theme == 4) {
                            echo "selected";
                          } ?>>eifo's cool blue theme</option>
      </select>
      <br>
      <button type="submit" name="submit" class="btn btn-primary">Submit</button>
    </form>
  </div>
</div>