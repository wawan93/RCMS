/**
 * @author Rev1lZ<mrrev1lz@gmail.com>
 * @version 1.0
 *
 * GB JS File
 */
app.gb = {};

app.gb.post = function(eForm) {
  rcms.ajax(
    rcms.path + 'GB',
    $(eForm).serialize(),
    function(data) {
      if (data.add.code == 0)
        eForm.reset();

       rcms.alert(data.add.type, data.add.message);

       $('#cms_gb-posts-num').html(data.posts.num);
       $('#cms_gb-posts').html(data.posts.rows);
  });
};