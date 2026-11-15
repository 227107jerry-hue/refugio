(function () {
  var boton = document.querySelector('.menu-btn');
  var menu = document.getElementById('menu');
  if (boton && menu) {
    boton.addEventListener('click', function () {
      var abierto = menu.classList.toggle('abierto');
      boton.setAttribute('aria-expanded', abierto ? 'true' : 'false');
    });
  }

  document.querySelectorAll('[data-mostrar]').forEach(function (btn) {
    var campo = document.getElementById(btn.getAttribute('data-mostrar'));
    if (!campo) return;
    btn.addEventListener('click', function () {
      var oculto = campo.type === 'password';
      campo.type = oculto ? 'text' : 'password';
      btn.textContent = oculto ? 'Ocultar' : 'Mostrar';
      btn.setAttribute('aria-pressed', oculto ? 'true' : 'false');
    });
  });
})();
