// フォームの要素を取得
var form = document.getElementById("form");

// ユーザー名の候補を格納する配列を作成する
var candidates = [];

// user_tbのusernameから候補を取得する関数を定義する
function getCandidates() {
  // Ajaxを使ってサーバーにリクエストを送る
  var xhr = new XMLHttpRequest();
  xhr.open("GET", "get_candidates.php", true);
  xhr.onreadystatechange = function() {
    if (xhr.readyState == 4 && xhr.status == 200) {
      // レスポンスをJSON形式でパースする
      var data = JSON.parse(xhr.responseText);
      // 配列に候補を追加する
      for (var i = 0; i < data.length; i++) {
        candidates.push(data[i].username);
      }
    }
  };
  xhr.send();
}

// フォームに入力された文字列を取得する関数を定義する
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

// 候補を表示する関数を定義する
function showCandidates(query) {
  // 候補を表示する要素を取得する
  var list = document.getElementById("list");
  // 候補を表示する要素を空にする
  list.innerHTML = "";
  // 候補の配列をループする
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
