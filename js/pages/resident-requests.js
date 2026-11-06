"use strict";

(function (modules) {
  modules.shared.initializeMobileNavigation();
  modules.accounts.initializeAccountAccess();
  modules.feedback.initializeFormFeedback();
  modules.resident.initializeResidentRequests();
})(window.IBacolorModules);
