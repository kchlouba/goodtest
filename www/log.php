<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width">

	<title>Login</title>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js" integrity="sha384-LtrjvnR4Twt/qOuYxE721u19sVFLVSA4hf/rRt6PrZTmiPltdZcI7q7PXQBYTKyf" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
  <link rel="stylesheet" href="styles.css" type="text/css" media="screen" />


</head>

<body>
<form action="http://nette/" method="post" id="loginform">

<div class="container">
  <label for="uname"><b>Login</b></label>
  <input type="text" placeholder="Enter Login" name="login" required>

  <label for="psw"><b>Password</b></label>
  <input type="password" placeholder="Enter Password" name="pass" required>

  <button type="submit">Login</button>
</div>


<div class="container" style="background-color:#f1f1f1">
  <button type="button" class="cancelbtn">Cancel</button>
</div>
</form>
<div id='userview' style='display:none;' >
<table id='userlist' class='table'>
<thead>
    <tr>
      <th>Login</th>
      <th>Pass</th>
    </tr>
  </thead>
  <tbody>

  </tbody>
</table>

<div id='pagination' style="display:flex;">
<button id="minus">-</button><div id='page' style="display:flex;"></div><button id="plus">+</button>
</div></div>

<script>
let ipage = 1;

function rr(data) {
  $('.current').removeClass('current');
  $('#b'+ipage).addClass('current');
  if (ipage==1) $('#minus').prop('disabled', true); else $('#minus').prop('disabled', false);
  if (ipage==data.pages) $('#plus').prop('disabled', true); else $('#plus').prop('disabled', false);
  $('#userlist tbody').empty();
  Object.values(data).forEach(item => {
      if (typeof item == 'object')
      $('#userlist tbody').append('<tr><td>'+item.Login+'</td><td>'+item.Pass+'</td></tr>')
      })
}

function checkServer(data=null) {
  let req = {
    processData: false,
    contentType: false,
    data: data,
    method: data==null?'GET':'POST'
}
  

  $.ajax('http://nette/', req).done(function( data ) {
   console.log(data);
   if (data.status!='fucked') {
     $('#loginform').hide(); 
     $('#userview').show();
     
      for( i=1; i<=data.pages;i++)  { 
        let pgbtn = $('<button class="primary" data-page="'+i+'" id="b'+i+'">'+i+'</button>')
        pgbtn.click(function(e) {
        let page=$(this).data('page')
        $.ajax('http://nette/?page='+page, req).done(function( data ) { 
          rr(data);
        })
        ipage = page;
        
        })
        $('#page').append(pgbtn);   
      }

      $('#plus').click(function(e) {
          ipage=ipage+1;
          $.ajax('http://nette/?page='+ipage, req).done(function( data ) { 
          rr(data);
        })
        })

      $('#minus').click(function(e) {
          ipage=ipage-1;
          $.ajax('http://nette/?page='+ipage, req).done(function( data ) { 
          rr(data);
        })
        }) 

     rr(data);
      
   }
    
  });
}

$(document).ready(function() {
  checkServer();
  $('#loginform').on('submit', (e) => { 

      e.preventDefault();
      let formData = new FormData($('#loginform')[0]); // Create an arbitrary FormData instance
      checkServer(formData)

      console.log($('#loginform')[0])

  })
})





</script>
</body>
</html>

