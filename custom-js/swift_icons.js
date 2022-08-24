document.addEventListener("DOMContentLoaded", function() {

    var customIcons = {
        'icon-swift-manage-subscriptions': 29,
        'icon-swift-edit-company': 23,
        'icon-swift-manage-departments': 21,
        'icon-swift-optional-profiles' : 6,
        'icon-swift-email-templates' : 10,
        'icon-swift-create-user' : 4,
        'icon-swift-edit-users' : 9,
        'icon-swift-merge-user-accounts': 12,
        'icon-swift-department-users-managers' : 73,
        'icon-swift-create-company': 17,
        'icon-swift-manage-companies': 65,
        'icon-swift-restrict-capabilities': 9,
        'icon-swift-create-course': 16,
        'icon-swift-manage-iomad-course-settings': 31,
        'icon-swift-teaching-locations': 5,
        'icon-swift-manage-iomad-template-settings': 41,
        'icon-swift-license-management': 34,
        'icon-swift-courses': 28,
        'icon-swift-orders': 13,
        'icon-swift-microlearning': 83,
        'icon-swift-assign-to-company2' : 13,
        'icon-swift-upload-users' : 5,
        'icon-swift-user-bulk-download' : 6,
        'icon-swift-approve-training-events' : 5,
        'icon-swift-iomad-company-overview-report': 29,
        'icon-swift-user-enrolments' : 36,
        'icon-swift-manage-company-groups' : 9,
        'icon-swift-assign-course-groups' : 6,
        'icon-swift-learning-paths' : 27,
        'icon-swift-user-license-allocations' : 29,
        'icon-swift-manage-iomad-framework-settings' : 45,
        'icon-swift-assign-frameworks-to-company' : 35,
        'icon-swift-assign-learning-plan-templates-to-company' : 31,
        'icon-swift-competency-frameworks' : 43,
        'icon-swift-learning-plan-templates' : 39,
        'icon-swift-attendance-report-by-course' : 38,
        'icon-swift-completion-report-by-course' : 17,
        'icon-swift-completion-report-by-month' : 39,
        'icon-swift-outgoing-email-report' : 16,
        'icon-swift-license-allocations-report' : 22,
        'icon-swift-user-license-allocations-report' : 21,
        'icon-swift-user-login-report' : 16,
        'icon-swift-users-report' : 15
    };

    function addColorfulIcons(icons) {
        
        icons.forEach((icon) => {
            
            var className = icon.getAttribute("class");
            
            var c = className.match(/icon-swift-[^\s'"]+/);
            
            var iconName = c && c[0].length > 10 ? c[0] : undefined;
            
            if (iconName && customIcons[iconName]) {
                var layers = customIcons[iconName];
                console.log(layers);
                addLayers(icon, layers);
            }
            
        });
        
        // icons.each(function() {
        //     if (!$(this).find("[class^='path']").length) {
        //         var className = $(this).attr("class");
        //         var c = className.match(/v-icon-[^\s'"]+/);
        //         var iconName = c && c[0].length > 7 ? c[0].substring(7) : undefined;
                // if (iconName && customIcons[iconName]) {
                //     var layers = customIcons[iconName];
                //     addLayers($(this), layers);
                // }
        //     } 
        // });
    };

    function addLayers(icon, numberOfLayers) {
        for (var i = 0; i < numberOfLayers; i++) {
            icon.innerHTML += "<span class=\"path" + (i + 1) + "\"></span>";
        }
    };

    function initCustomIcons() {
        // var icons = $("[class^='v-icon-']");
        
        var icons = document.querySelectorAll("[class^='icon-swift']");
        
        var keys = Object.keys(customIcons);

        if (keys.length) {
            addColorfulIcons(icons);
        }
    };

    initCustomIcons();
  
});
