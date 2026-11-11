document.addEventListener("DOMContentLoaded", function () {
    const tabs = document.querySelectorAll("[data-panel]");
    const panels = document.querySelectorAll(".form-panel");

    function showPanel(panelId) {
        panels.forEach(panel => panel.classList.toggle("hidden", panel.id !== panelId));
        tabs.forEach(tab => tab.classList.toggle("active", tab.dataset.panel === panelId));
    }

    tabs.forEach(tab => tab.addEventListener("click", () => showPanel(tab.dataset.panel)));
    document.querySelectorAll("[data-show]").forEach(button => {
        button.addEventListener("click", () => showPanel(button.dataset.show));
    });

    // If the URL contains a registration error, open the registration form.
    if (window.location.hash === "#registerPanel") showPanel("registerPanel");
});
