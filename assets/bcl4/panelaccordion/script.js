const BclPanelAccordion = {
  init() {
    document.body.addEventListener('click', (e) => {
      const link = e.target.closest('.osy-panel-accordion .panel-heading a');
      if (!link) return;

      const panelId = link.dataset.panelId;
      const panelGroup = link.closest('.panel-group');
      const openId = panelGroup.querySelector('input').value;

      panelGroup.querySelectorAll('.expanded').forEach((el) => el.classList.remove('expanded'));

      if (panelId !== openId) {
        panelGroup.querySelector('input').value = panelId;
        link.closest('.panel').classList.add('expanded');
      }
    });
  }
};

if (window.Osynapsy) {
  window.Osynapsy.plugin.register('BclPanelAccordion', () => BclPanelAccordion.init());
}