import "bootstrap";

// print out the year
const year = document.getElementById("year");
year.innerHTML = new Date().getFullYear();

// =============================================
// ローディング画面 (this func add css class)
// =============================================
function loadedPage() {
  const loadingID = document.getElementById("js_loading");
  loadingID.classList.add("loaded");
}

// if there's no sessionStorage, add it and fire the function
if (!sessionStorage.getItem("visited")) {
  sessionStorage.setItem("visited", "first");
  window.addEventListener("load", function () {
    setTimeout(loadedPage, 2000);
  });
  setTimeout(loadedPage, 2000);

  // otherwise just fire the function so that you don't see loading animation
} else {
  loadedPage();
}
