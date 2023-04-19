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

// 現在のページのパスを取得する ex) /php/blog/Login.php
var currentPagePath = window.location.pathname;

// 現在のURLを取得する ex) http://localhost:8888/php/blog/Login.php
// const currentPageUrl = window.location.href;

// if there's no sessionStorage, add it and fire the function
if (!sessionStorage.getItem("visited")) {

  // ログインページのみ適用 (change this when you upload server)
  if (currentPagePath === "/php/blog/Login.php") {

    // アニメーションを出さない
    window.addEventListener("load", function () {
      setTimeout(loadedPage, 0);
    });
    setTimeout(loadedPage, 0);

    // ログインページ以外なら sessionStorageに格納し、アニメーション出す
  } else {
    sessionStorage.setItem("visited", "first");
    window.addEventListener("load", function () {
      setTimeout(loadedPage, 1000);
    });
    setTimeout(loadedPage, 1000);
  }

  // otherwise just fire the function so that you don't see loading animation
} else {
  loadedPage();
}
