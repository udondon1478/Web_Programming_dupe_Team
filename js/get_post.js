$(function () {
  //nl2br関数の定義
  function nl2br(str) {
    return str.replace(/\n/g, "<br>");
  }

  //レコードの全件取得
  function getAllData() {
    $.ajax({
      url: "ajax_show_all.php",
    }).then(
      //成功時の処理
      function (data) {
        console.log("通信成功");
        //取得した内容をコンソールに出力
        console.log(data);
        //取得したレコードをeachで順次取得
        $.each(data, function (key, element) {
          // #all_show_result内にappendで追記
          $("#all_show_result").append(
            "<tr><td>" +
              element.id +
              "</td><td>" +
              element.title +
              "</td><td>" +
              nl2br(element.content) +
              "</td><td>" +
              element.name +
              "</td><td>" +
              element.created_at +
              "</td></tr>"
          );
        });
      },
      //エラー時の処理
      function (XMLHttpRequest, textStatus, errorThrown) {
        console.log(
          "エラーが発生しました:" +
            XMLHttpRequest.status +
            ":\n" +
            textStatus +
            ":\n" +
            errorThrown
        );
      }
    );
  }
  //関数の実行
  getAllData();

  //#ajax_addがクリックされた時の処理
  $("#ajax_add").on("click", function () {
    console.log("「追加」ボタンがクリックされました(add)");
    //確認メッセージを表示

    //OKならtrue,キャンセルならfalseが代入される
    var confirm_result = window.confirm("登録してもよろしいですか？");
    if (confirm_result) {
      //登録時
      $.ajax({
        //送信方法
        type: "POST",
        //送信先ファイル名
        url: "ajax_add.php",
        //受け取りデータの種類
        datatype: "json",
        //送信データ
        data: {
          //#titleと#messageのvalueをセット
          title: $("#title").val(),
          message: $("#message").val(),
        },
      }).then(
        //成功時の処理
        function (data) {
          $("#add_result").html(
            "<p>「" +
              data[0].message_title +
              "」で<br>「" +
              nl2br(data[0].message) +
              "」<br>が登録されました</p>"
          );
          //一覧に追加したレコードの追記
          $.each(data, function (key, element) {
            //ヘッダを削除
            $("#all_show_result tr:first").remove();
            $("#all_show_result").prepend(
              "<tr><td>" +
                element.message_id +
                "</td><td>" +
                element.message_title +
                "</td><td>" +
                nl2br(element.message) +
                "</td><td>" +
                element.user_name +
                "</td><td>" +
                element.entry_date +
                "</td></tr>"
            );
            //ヘッダを先頭に追加
            $("#all_show_result").prepend(
              "<tr><th>ID</th><th>タイトル</th><th>メッセージ</th><th>ユーザ</th><th>投稿日時</th></tr>"
            );
          });
          console.log("通信成功(add)");
        },
        function (XMLHttpRequest, textStatus, errorThrown) {
          console.log("通信失敗!!! (add)");
          console.log("XMLHttpRequest : " + XMLHttpRequest.status);
          console.log("textStatus     : " + textStatus);
          console.log("errorThrown    : " + errorThrown.message);
        }
      );
    }
    return false;
  });

  //検索用のボックスが更新されるたびに呼ばれる
  $("#search_message").on("input", function () {
    console.log("search_message呼ばれた");
    $.ajax({
      //送信方法
      type: "POST",
      //送信先ファイル名
      url: "ajax_search.php",
      //受け取りデータの種類
      datatype: "json",
      //送信データ
      data: {
        search_message: $("#search_message").val(),
      },
    }).then(
      //成功時の処理
      function (data) {
        //ヘッダ以外削除
        $("#all_show_result tr:not(:first)").remove();
        //一覧に追加したレコードの追記
        $.each(data, function (key, value) {
          // #all_show_result内にappendで追記
          $("#all_show_result").append(
            "<tr><td>" +
              value.message_id +
              "</td><td>" +
              value.message_title +
              "</td><td>" +
              nl2br(value.message) +
              "</td><td>" +
              value.user_name +
              "</td><td>" +
              value.entry_date +
              "</td></tr>"
          );
        });
        console.log("通信成功(search_message)");
      }
    );
  });
});
