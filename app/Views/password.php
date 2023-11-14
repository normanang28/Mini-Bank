<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="card">
        <div class="card-body">
            <div class="basic-form">
              <form class="form-horizontal form-label-left" novalidate  action="<?= base_url('home/aksi_change_pw')?>" method="post">

                    <div class="item form-group">
          <label class="control-label col-md-12 col-sm-12 col-xs-12">Password<span style="color: red;">*</span>
          </label>
          <div class="col-md-12 col-sm-12 col-xs-12">
            <input id="password" class="form-control col-md-12 col-xs-12" data-validate-length-range="6" data-validate-words="2" name="password" placeholder="Password" required="required" type="text" value="<?= $use->password?>">
          </div>
        </div>
         <div class="item form-group">
          <label class="control-label col-md-12 col-sm-12 col-xs-12">Change Your Password<span style="color: red;">*</span>
          </label>
          <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="input-group">
              <input type="password" placeholder="Change Your Password" name="p1" class="form-control col-md-12 col-xs-12" data-validate-length-range="6" data-validate-words="2" id="newPassword" >
              <span class="input-group-text" id="togglePassword"><i class="fa fa-eye" aria-hidden="true"></i></span>
            </div>
          </div>
        </div>
        <div class="item form-group">
          <label class="control-label col-md-12 col-sm-12 col-xs-12">Verify Your Password<span style="color: red;">*</span>
          </label>
          <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="input-group">
            <input type="password" placeholder="Verify Your Password" name="pw" class="form-control col-md-12 col-xs-12" data-validate-length-range="6" data-validate-words="2" id="verifyPassword">
            <span class="input-group-text" id="togglePasswordVerify"><i class="fa fa-eye" aria-hidden="true"></i></span>
          </div>
        </div>
      </div>
      <small class="form-text text-danger" id="passwordMismatchAlert" style="display: none;">Password must be the same.</small>
      <div class="ln_solid"></div>
      <div class="form-group">
        <div class="col-md-12 col-md-offset-12">
          <!-- <a onclick="history.back()" type="submit" class="btn btn-primary">Cancel</a></button> -->
          <button id="submitButton" type="submit" class="btn btn-success" disabled>Update</button>
        </div>
      </div>
    </form>
    </div>
</div>
</div>
</div>

<script>
    const newPasswordInput = document.getElementById('newPassword');
    const verifyPasswordInput = document.getElementById('verifyPassword');
    const submitButton = document.getElementById('submitButton');
    const passwordMismatchAlert = document.getElementById('passwordMismatchAlert');
    
    verifyPasswordInput.addEventListener('input', function() {
        if (verifyPasswordInput.value === newPasswordInput.value) {
            submitButton.disabled = false;
            passwordMismatchAlert.style.display = 'none';
        } else {
            submitButton.disabled = true;
            passwordMismatchAlert.style.display = 'block';
        }
    });

    const togglePassword = document.getElementById('togglePassword');
    togglePassword.addEventListener('click', function () {
        const type = newPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        newPasswordInput.setAttribute('type', type);
    });

    const togglePasswordVerify = document.getElementById('togglePasswordVerify');
    togglePasswordVerify.addEventListener('click', function () {
        const type = verifyPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        verifyPasswordInput.setAttribute('type', type);
    });
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    let timeout;
    const timeoutDuration = 2 * 60 * 1000; 

    function startTimeout() {
        clearTimeout(timeout);
        timeout = setTimeout(redirectToDashboard, timeoutDuration);
    }

    function redirectToDashboard() {
        window.location.href = '<?= base_url('/home/dashboard') ?>';
    }

    document.addEventListener('mousemove', startTimeout);
    document.addEventListener('keypress', startTimeout);

    startTimeout();

    const tableBody = document.querySelector('.table tbody');
    const pageNumbers = document.getElementById('pageNumbers');

    const data = <?= json_encode($duar) ?>; 
    const itemsPerPage = 50;
    let currentPage = 1;
});
</script>
