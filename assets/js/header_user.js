document.addEventListener('DOMContentLoaded', () => {
  const avatar = document.querySelector('.avatar');
  const dropdown = document.querySelector('.dropdown');

  if (avatar && dropdown) {
    let isOpen = false;

    avatar.addEventListener('click', (e) => {
      e.stopPropagation();
      isOpen = !isOpen;
      dropdown.classList.toggle('show-dropdown', isOpen);
    });

    document.addEventListener('click', () => {
      if (isOpen) {
        dropdown.classList.remove('show-dropdown');
        isOpen = false;
      }
    });
  }
});
 