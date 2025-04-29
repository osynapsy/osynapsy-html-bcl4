const BclTab = {
  init() {
    document.querySelectorAll('.nav-tabs').forEach((nav) => {
      nav.querySelectorAll('a').forEach((link) => {
        link.addEventListener('click', () => {
          const tabId = link.getAttribute('href');
          const navId = nav.id.replace('_nav', '');
          document.getElementById(navId).value = tabId;
        });
      });
    });
  }
};

if (window.Osynapsy) {
  window.Osynapsy.plugin.register('BclTab_Init', () => BclTab.init());
}