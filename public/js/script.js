$(document).ready(function () {

  $('.accordion-header').on('click', function () {

    const $header = $(this);

    const $content = $header.next('.accordion-content');

    $header.toggleClass('active');

    $content.toggleClass('open');

    $('.accordion-header').not($header).removeClass('active');
    $('.accordion-content').not($content).removeClass('open');
  });
});
