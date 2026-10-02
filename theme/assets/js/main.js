(function () {
  var heroAnswer = document.getElementById('hero-answer');
  var claridad = document.getElementById('f-claridad');
  var form = document.getElementById('apply-form');
  var steps = Array.prototype.slice.call(form.querySelectorAll('.step'));
  var next = document.getElementById('next');
  var back = document.getElementById('back');
  var actions = document.getElementById('actions');
  var bar = document.getElementById('progress-bar');
  var progressText = document.getElementById('progress-text');
  var sendError = document.getElementById('send-error');
  var tel = document.getElementById('f-tel');
  var telHint = document.getElementById('tel-hint');
  var loadedAt = Date.now();
  var current = 1;
  var TOTAL = 3;

  // The hero textarea grows with its content
  heroAnswer.addEventListener('input', function () {
    heroAnswer.style.height = 'auto';
    heroAnswer.style.height = heroAnswer.scrollHeight + 'px';
  });

  // The hero answer becomes step 1 of the application
  document.getElementById('hero-continue').addEventListener('click', function () {
    var text = heroAnswer.value.trim();
    if (text) { claridad.value = text; show(2); } else { show(1); }
    document.getElementById('formulario').scrollIntoView();
    var target = form.querySelector('.step.is-on input, .step.is-on textarea');
    if (target) { target.focus({ preventScroll: true }); }
  });

  // Phone is only required when WhatsApp is the preferred contact method
  form.querySelectorAll('input[name="contacto"]').forEach(function (r) {
    r.addEventListener('change', function () {
      var wa = form.elements.contacto.value === 'WhatsApp';
      tel.required = wa;
      telHint.textContent = wa ? '' : '(opcional)';
      tel.closest('.field').classList.remove('has-error');
    });
  });

  function show(n) {
    current = n;
    steps.forEach(function (s) { s.classList.toggle('is-on', s.dataset.step === String(n)); });
    if (n === 'done') {
      actions.hidden = true;
      progressText.textContent = 'Listo';
      bar.style.width = '100%';
      form.querySelector('.done').focus();
      return;
    }
    back.hidden = n === 1;
    next.textContent = n === TOTAL ? 'Enviar aplicación' : 'Continuar';
    progressText.textContent = 'Paso ' + n + ' de ' + TOTAL;
    bar.style.width = (n / TOTAL * 100) + '%';
  }

  function validate(n) {
    var ok = true;
    var step = form.querySelector('.step[data-step="' + n + '"]');
    step.querySelectorAll('.field').forEach(function (field) {
      var input = field.querySelector('input, textarea');
      var value = input.value.trim();
      var bad = input.required && !value;
      if (!bad && input.type === 'email') { bad = !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value); }
      field.classList.toggle('has-error', bad);
      if (bad && ok) { input.focus(); }
      if (bad) { ok = false; }
    });
    return ok;
  }

  form.addEventListener('input', function (e) {
    var field = e.target.closest('.field');
    if (field) { field.classList.remove('has-error'); }
  });

  back.addEventListener('click', function () { if (current > 1) { show(current - 1); } });

  next.addEventListener('click', function () {
    if (!validate(current)) { return; }
    if (current < TOTAL) { show(current + 1); return; }
    send();
  });

  function send() {
    sendError.style.display = 'none';
    next.disabled = true;
    next.textContent = 'Enviando…';
    var data = new FormData(form);

    data.append('action', 'fr_apply');
    data.append('fr_t', String(Math.round((Date.now() - loadedAt) / 1000)));
    var request = fetch(window.FR_APPLY.url, { method: 'POST', body: data, credentials: 'same-origin' })
      .then(function (r) { return r.json(); })
      .then(function (j) { if (!j || !j.success) { throw new Error('envio'); } });

    request.then(function () { show('done'); })
      .catch(function () {
        sendError.style.display = 'block';
        next.textContent = 'Enviar aplicación';
      })
      .then(function () { next.disabled = false; });
  }
})();
