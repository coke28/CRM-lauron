/*
 * ATTENTION: An "eval-source-map" devtool has been used.
 * This devtool is neither made for production nor for readable output files.
 * It uses "eval()" calls to create a separate source file with attached SourceMaps in the browser devtools.
 * If you are trying to read the output file, select a different devtool (https://webpack.js.org/configuration/devtool/)
 * or disable the default devtool with "devtool: false".
 * If you are looking for production-ready output files, see mode: "production" (https://webpack.js.org/configuration/mode/).
 */
/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./resources/assets/demo1/js/custom/modals/create-project/bundle/team.js":
/*!*******************************************************************************!*\
  !*** ./resources/assets/demo1/js/custom/modals/create-project/bundle/team.js ***!
  \*******************************************************************************/
/***/ ((module) => {

eval(" // Class definition\n\nvar KTModalCreateProjectTeam = function () {\n  // Variables\n  var nextButton;\n  var previousButton;\n  var form;\n  var stepper; // Private functions\n\n  var handleForm = function handleForm() {\n    nextButton.addEventListener('click', function (e) {\n      // Prevent default button action\n      e.preventDefault(); // Disable button to avoid multiple click \n\n      nextButton.disabled = true; // Show loading indication\n\n      nextButton.setAttribute('data-kt-indicator', 'on'); // Simulate form submission\n\n      setTimeout(function () {\n        // Enable button\n        nextButton.disabled = false; // Simulate form submission\n\n        nextButton.removeAttribute('data-kt-indicator'); // Go to next step\n\n        stepper.goNext();\n      }, 1500);\n    });\n    previousButton.addEventListener('click', function () {\n      stepper.goPrevious();\n    });\n  };\n\n  return {\n    // Public functions\n    init: function init() {\n      form = KTModalCreateProject.getForm();\n      stepper = KTModalCreateProject.getStepperObj();\n      nextButton = document.querySelector('[data-kt-element=\"team-next\"]');\n      previousButton = document.querySelector('[data-kt-element=\"team-previous\"]');\n      handleForm();\n    }\n  };\n}(); // Webpack support\n\n\nif ( true && typeof module.exports !== 'undefined') {\n  module.exports = KTModalCreateProjectTeam;\n}//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiLi9yZXNvdXJjZXMvYXNzZXRzL2RlbW8xL2pzL2N1c3RvbS9tb2RhbHMvY3JlYXRlLXByb2plY3QvYnVuZGxlL3RlYW0uanMuanMiLCJtYXBwaW5ncyI6IkNBRUE7O0FBQ0EsSUFBSUEsd0JBQXdCLEdBQUcsWUFBWTtBQUMxQztBQUNBLE1BQUlDLFVBQUo7QUFDQSxNQUFJQyxjQUFKO0FBQ0EsTUFBSUMsSUFBSjtBQUNBLE1BQUlDLE9BQUosQ0FMMEMsQ0FPMUM7O0FBQ0EsTUFBSUMsVUFBVSxHQUFHLFNBQWJBLFVBQWEsR0FBVztBQUMzQkosSUFBQUEsVUFBVSxDQUFDSyxnQkFBWCxDQUE0QixPQUE1QixFQUFxQyxVQUFVQyxDQUFWLEVBQWE7QUFDakQ7QUFDQUEsTUFBQUEsQ0FBQyxDQUFDQyxjQUFGLEdBRmlELENBSWpEOztBQUNBUCxNQUFBQSxVQUFVLENBQUNRLFFBQVgsR0FBc0IsSUFBdEIsQ0FMaUQsQ0FPakQ7O0FBQ0FSLE1BQUFBLFVBQVUsQ0FBQ1MsWUFBWCxDQUF3QixtQkFBeEIsRUFBNkMsSUFBN0MsRUFSaUQsQ0FVakQ7O0FBQ0FDLE1BQUFBLFVBQVUsQ0FBQyxZQUFXO0FBQ3JCO0FBQ0FWLFFBQUFBLFVBQVUsQ0FBQ1EsUUFBWCxHQUFzQixLQUF0QixDQUZxQixDQUlyQjs7QUFDQVIsUUFBQUEsVUFBVSxDQUFDVyxlQUFYLENBQTJCLG1CQUEzQixFQUxxQixDQU9yQjs7QUFDQVIsUUFBQUEsT0FBTyxDQUFDUyxNQUFSO0FBQ0EsT0FUUyxFQVNQLElBVE8sQ0FBVjtBQVVBLEtBckJEO0FBdUJBWCxJQUFBQSxjQUFjLENBQUNJLGdCQUFmLENBQWdDLE9BQWhDLEVBQXlDLFlBQVk7QUFDcERGLE1BQUFBLE9BQU8sQ0FBQ1UsVUFBUjtBQUNBLEtBRkQ7QUFHQSxHQTNCRDs7QUE2QkEsU0FBTztBQUNOO0FBQ0FDLElBQUFBLElBQUksRUFBRSxnQkFBWTtBQUNqQlosTUFBQUEsSUFBSSxHQUFHYSxvQkFBb0IsQ0FBQ0MsT0FBckIsRUFBUDtBQUNBYixNQUFBQSxPQUFPLEdBQUdZLG9CQUFvQixDQUFDRSxhQUFyQixFQUFWO0FBQ0FqQixNQUFBQSxVQUFVLEdBQUdrQixRQUFRLENBQUNDLGFBQVQsQ0FBdUIsK0JBQXZCLENBQWI7QUFDQWxCLE1BQUFBLGNBQWMsR0FBR2lCLFFBQVEsQ0FBQ0MsYUFBVCxDQUF1QixtQ0FBdkIsQ0FBakI7QUFFQWYsTUFBQUEsVUFBVTtBQUNWO0FBVEssR0FBUDtBQVdBLENBaEQ4QixFQUEvQixDLENBa0RBOzs7QUFDQSxJQUFJLFNBQWlDLE9BQU9nQixNQUFNLENBQUNDLE9BQWQsS0FBMEIsV0FBL0QsRUFBNEU7QUFDM0VELEVBQUFBLE1BQU0sQ0FBQ0MsT0FBUCxHQUFpQnRCLHdCQUFqQjtBQUNBIiwic291cmNlcyI6WyJ3ZWJwYWNrOi8vLy4vcmVzb3VyY2VzL2Fzc2V0cy9kZW1vMS9qcy9jdXN0b20vbW9kYWxzL2NyZWF0ZS1wcm9qZWN0L2J1bmRsZS90ZWFtLmpzP2VhOGEiXSwic291cmNlc0NvbnRlbnQiOlsiXCJ1c2Ugc3RyaWN0XCI7XHJcblxyXG4vLyBDbGFzcyBkZWZpbml0aW9uXHJcbnZhciBLVE1vZGFsQ3JlYXRlUHJvamVjdFRlYW0gPSBmdW5jdGlvbiAoKSB7XHJcblx0Ly8gVmFyaWFibGVzXHJcblx0dmFyIG5leHRCdXR0b247XHJcblx0dmFyIHByZXZpb3VzQnV0dG9uO1xyXG5cdHZhciBmb3JtO1xyXG5cdHZhciBzdGVwcGVyO1xyXG5cclxuXHQvLyBQcml2YXRlIGZ1bmN0aW9uc1xyXG5cdHZhciBoYW5kbGVGb3JtID0gZnVuY3Rpb24oKSB7XHJcblx0XHRuZXh0QnV0dG9uLmFkZEV2ZW50TGlzdGVuZXIoJ2NsaWNrJywgZnVuY3Rpb24gKGUpIHtcclxuXHRcdFx0Ly8gUHJldmVudCBkZWZhdWx0IGJ1dHRvbiBhY3Rpb25cclxuXHRcdFx0ZS5wcmV2ZW50RGVmYXVsdCgpO1xyXG5cclxuXHRcdFx0Ly8gRGlzYWJsZSBidXR0b24gdG8gYXZvaWQgbXVsdGlwbGUgY2xpY2sgXHJcblx0XHRcdG5leHRCdXR0b24uZGlzYWJsZWQgPSB0cnVlO1xyXG5cclxuXHRcdFx0Ly8gU2hvdyBsb2FkaW5nIGluZGljYXRpb25cclxuXHRcdFx0bmV4dEJ1dHRvbi5zZXRBdHRyaWJ1dGUoJ2RhdGEta3QtaW5kaWNhdG9yJywgJ29uJyk7XHJcblxyXG5cdFx0XHQvLyBTaW11bGF0ZSBmb3JtIHN1Ym1pc3Npb25cclxuXHRcdFx0c2V0VGltZW91dChmdW5jdGlvbigpIHtcclxuXHRcdFx0XHQvLyBFbmFibGUgYnV0dG9uXHJcblx0XHRcdFx0bmV4dEJ1dHRvbi5kaXNhYmxlZCA9IGZhbHNlO1xyXG5cdFx0XHRcdFxyXG5cdFx0XHRcdC8vIFNpbXVsYXRlIGZvcm0gc3VibWlzc2lvblxyXG5cdFx0XHRcdG5leHRCdXR0b24ucmVtb3ZlQXR0cmlidXRlKCdkYXRhLWt0LWluZGljYXRvcicpO1xyXG5cdFx0XHRcdFxyXG5cdFx0XHRcdC8vIEdvIHRvIG5leHQgc3RlcFxyXG5cdFx0XHRcdHN0ZXBwZXIuZ29OZXh0KCk7XHJcblx0XHRcdH0sIDE1MDApOyBcdFx0XHJcblx0XHR9KTtcclxuXHJcblx0XHRwcmV2aW91c0J1dHRvbi5hZGRFdmVudExpc3RlbmVyKCdjbGljaycsIGZ1bmN0aW9uICgpIHtcclxuXHRcdFx0c3RlcHBlci5nb1ByZXZpb3VzKCk7XHJcblx0XHR9KTtcclxuXHR9XHJcblxyXG5cdHJldHVybiB7XHJcblx0XHQvLyBQdWJsaWMgZnVuY3Rpb25zXHJcblx0XHRpbml0OiBmdW5jdGlvbiAoKSB7XHJcblx0XHRcdGZvcm0gPSBLVE1vZGFsQ3JlYXRlUHJvamVjdC5nZXRGb3JtKCk7XHJcblx0XHRcdHN0ZXBwZXIgPSBLVE1vZGFsQ3JlYXRlUHJvamVjdC5nZXRTdGVwcGVyT2JqKCk7XHJcblx0XHRcdG5leHRCdXR0b24gPSBkb2N1bWVudC5xdWVyeVNlbGVjdG9yKCdbZGF0YS1rdC1lbGVtZW50PVwidGVhbS1uZXh0XCJdJyk7XHJcblx0XHRcdHByZXZpb3VzQnV0dG9uID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvcignW2RhdGEta3QtZWxlbWVudD1cInRlYW0tcHJldmlvdXNcIl0nKTtcclxuXHJcblx0XHRcdGhhbmRsZUZvcm0oKTtcclxuXHRcdH1cclxuXHR9O1xyXG59KCk7XHJcblxyXG4vLyBXZWJwYWNrIHN1cHBvcnRcclxuaWYgKHR5cGVvZiBtb2R1bGUgIT09ICd1bmRlZmluZWQnICYmIHR5cGVvZiBtb2R1bGUuZXhwb3J0cyAhPT0gJ3VuZGVmaW5lZCcpIHtcclxuXHRtb2R1bGUuZXhwb3J0cyA9IEtUTW9kYWxDcmVhdGVQcm9qZWN0VGVhbTtcclxufSJdLCJuYW1lcyI6WyJLVE1vZGFsQ3JlYXRlUHJvamVjdFRlYW0iLCJuZXh0QnV0dG9uIiwicHJldmlvdXNCdXR0b24iLCJmb3JtIiwic3RlcHBlciIsImhhbmRsZUZvcm0iLCJhZGRFdmVudExpc3RlbmVyIiwiZSIsInByZXZlbnREZWZhdWx0IiwiZGlzYWJsZWQiLCJzZXRBdHRyaWJ1dGUiLCJzZXRUaW1lb3V0IiwicmVtb3ZlQXR0cmlidXRlIiwiZ29OZXh0IiwiZ29QcmV2aW91cyIsImluaXQiLCJLVE1vZGFsQ3JlYXRlUHJvamVjdCIsImdldEZvcm0iLCJnZXRTdGVwcGVyT2JqIiwiZG9jdW1lbnQiLCJxdWVyeVNlbGVjdG9yIiwibW9kdWxlIiwiZXhwb3J0cyJdLCJzb3VyY2VSb290IjoiIn0=\n//# sourceURL=webpack-internal:///./resources/assets/demo1/js/custom/modals/create-project/bundle/team.js\n");

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module is referenced by other modules so it can't be inlined
/******/ 	var __webpack_exports__ = __webpack_require__("./resources/assets/demo1/js/custom/modals/create-project/bundle/team.js");
/******/ 	
/******/ })()
;