function cancelEditReply(replyId) {
  'use strict'
  document.getElementById('reply' + replyId).classList.remove('hidden');
  document.getElementById('edit' + replyId).classList.add('hidden');

  let targetShowReply = document.getElementById('body' + replyId).querySelector('.show_reply');
  targetShowReply.classList.remove('hidden');
}