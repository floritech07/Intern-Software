// Dark mode toggle
document.getElementById('dark-mode-toggle').addEventListener('click', function() {
  const main = document.querySelector('main');
  const buttonContainer = document.querySelector('.d-flex.justify-content-between.align-items-center');
  const sortContainer = document.querySelector('.sort-container');  // Cible la bonne classe

  main.classList.toggle('bg-dark-main');
  buttonContainer.classList.toggle('bg-dark-button-container');
  sortContainer.classList.toggle('bg-dark-sort-container');

  // Change the icon
  const icon = document.getElementById('dark-mode-icon');
  icon.classList.toggle('bi-moon-fill');
  icon.classList.toggle('bi-brightness-high-fill');
});
