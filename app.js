// Мәзір (мобильді)
const burger = document.querySelector('.burger'), menu = document.getElementById('menu');
burger?.addEventListener('click', () => {
  const open = menu.classList.toggle('open');
  burger.setAttribute('aria-expanded', open);
});
// Өшіру алдында растау
document.querySelectorAll('form[data-confirm]').forEach(f =>
  f.addEventListener('submit', e => { if (!confirm(f.dataset.confirm)) e.preventDefault(); }));
// Тіркелу формасын клиент жағында тексеру
const reg = document.getElementById('regForm');
reg?.addEventListener('submit', e => {
  const p = reg.password.value, p2 = reg.password2.value;
  if (p.length < 6 || p !== p2) { e.preventDefault(); alert('Құпиясөз кемінде 6 таңба және екі өріс бірдей болуы керек.'); }
});
