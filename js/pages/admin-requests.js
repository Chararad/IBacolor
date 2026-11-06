"use strict";

(function (modules) {
  modules.shared.initializeMobileNavigation();
  modules.accounts.initializeAccountAccess();
  modules.feedback.initializeFormFeedback();
  modules.filters.initializeRequestFilters();
})(window.IBacolorModules);
