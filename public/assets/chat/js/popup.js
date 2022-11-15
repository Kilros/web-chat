const { createPopup } = window.picmoPopup;

document.addEventListener('DOMContentLoaded', () => {
  // const selectionContainer = document.querySelector('#selection-outer');
  const emoji = document.querySelector('#msg');
  // const name = document.querySelector('#selection-name');
  const trigger = document.querySelector('#trigger');

  const picker = createPopup({}, {
    referenceElement: trigger,
    triggerElement: trigger,
    position: 'right-end'
  });

  trigger.addEventListener('click', () => {
    picker.toggle();
  });

  picker.addEventListener('emoji:select', (selection) => {
    let data = emoji.value;
    emoji.value =data+ selection.emoji;
    sendBtn.classList.add("active");
    // name.textContent = selection.label;
    // selectionContainer.classList.remove('empty');
  });
});
