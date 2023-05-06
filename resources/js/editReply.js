function editReply(replyId) {
  'use strict'
  document.getElementById('reply' + replyId).classList.add('hidden');
  document.getElementById('edit' + replyId).classList.remove('hidden');

  let targetShowReply = document.getElementById('body' + replyId).querySelector('.show_reply');
  targetShowReply.classList.add('hidden');
}