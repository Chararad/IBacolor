"use strict";

(function (modules) {
  modules.shared.initializeMobileNavigation();
  modules.accounts.initializeAccountAccess();
  modules.feedback.initializeFormFeedback();
  modules.services.initializeServiceDirectory();
})(window.IBacolorModules);
