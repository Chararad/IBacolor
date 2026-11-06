"use strict";

(function (modules) {
  modules.shared.initializeMobileNavigation();
  modules.accounts.initializeAccountAccess();
  modules.feedback.initializeFormFeedback();
  modules.shared.populateBarangaySelects();
  modules.reports.initializeReports();
})(window.IBacolorModules);
