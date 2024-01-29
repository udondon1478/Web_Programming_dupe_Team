// フォームの要素を取得
var form = document.getElementById("form");

// ユーザー名の候補を格納する配列を作成する
var candidates = [];

// user_tbのusernameから候補を取得する関数
function getCandidates() {
  // Ajaxを使ってサーバーにリクエストを送る
  $.ajax({
    url: "ajax_get_candidates.php",
  }).then(
    // 成功時
    function (data) {
      console.log("候補取得用通信成功");
      // ユーザー名の候補を配列に格納する
      candidates = data;
    },
    // 失敗時
    function (XMLHttpRequest, textStatus, errorThrown) {
      console.log("候補取得用通信失敗");
      console.log("XMLHttpRequest : " + XMLHttpRequest.status);
      console.log("textStatus     : " + textStatus);
      console.log("errorThrown    : " + errorThrown.message);
    }
  );
}

// フォームに入力された文字列を取得する関数
function getInput() {
  // フォームの値を取得する
  var input = form.value;
  // @マークが含まれているかチェックする
  if (input.includes("@")) {
    // @マークの後ろの文字列を取得する
    var query = input.split("@")[1];
    // 候補を表示する関数を呼び出す
    showCandidates(query);
  }
}

// 候補を表示する関数
function showCandidates(query) {
  // 候補を表示する要素を取得
  var list = document.getElementById("list");
  // 候補を表示する要素を空にする
  list.innerHTML = "";
  // 候補の配列をループ
  for (var i = 0; i < candidates.length; i++) {
    // 候補がクエリに一致するかチェックする
    if (candidates[i].startsWith(query)) {
      // 候補を表示する要素に追加する
      var item = document.createElement("li");
      item.textContent = candidates[i];
      list.appendChild(item);
    }
  }
}

// フォームに入力があったときにイベントリスナーを設定する
form.addEventListener("input", getInput);

// ページが読み込まれたときに候補を取得する関数を呼び出す
window.addEventListener("load", getCandidates);
