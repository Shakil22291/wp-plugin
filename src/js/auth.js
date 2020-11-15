document.addEventListener('DOMContentLoaded', function () {
  const showAuthBtn = document.querySelector('#mypluging-show-auth-form');
  const authContainer = document.querySelector('.auth-form-wrapper');
  const closeBtn = document.querySelector('#myplugin-auth-close');
  const authForm = document.getElementById('myplugin-auth-form');
  const status = authForm.querySelector('[data-message="status"]');

  showAuthBtn.addEventListener('click', function () {
    authContainer.classList.add('show');
    this.parentElement.classList.add('hide');
  });

  closeBtn.addEventListener('click', function () {
    authContainer.classList.remove('show');
    showAuthBtn.parentElement.classList.remove('hide');
  });

  authForm.addEventListener('submit', (e) => {
    e.preventDefault();

    let data = {
      name: authForm.querySelector('[name="username"]').value,
      password: authForm.querySelector('[name="password"]').value,
      nonce: authForm.querySelector('[name="myplugin_auth"]').value,
    };

    if (!data.name || !data.password) {
      status.innerHTML = 'Missing data';
      status.classList.add('error');
      return;
    }

    // ajax http post request
    let url = authForm.dataset.url;
    let params = new URLSearchParams(new FormData(authForm));
    authForm.querySelector('[name="submit"]').value = 'Logging in..';
    authForm.querySelector('[name="submit"]').disabled = true;

    fetch(url, {
      method: 'POST',
      body: params,
    })
      .then((resolve) => resolve.json())
      .catch((error) => {
        resetMessage();
        alert('reseting the message');
      })
      .then((response) => {
        resetMessage();
        if (!response.status || response === 0) {
          status.innerHTML = response.message;
          status.classList.add('error');

          return;
        }

        status.innerHTML = response.message;
        status.classList.add('success');

        window.location.reload();
      });
  });

  function resetMessage() {
    status.innerHTML = '';
    status.classList.remove('error', 'success');

    authForm.querySelector('[name="submit"]').value = 'Login';
    authForm.querySelector('[name="submit"]').disabled = false;
  }
});
