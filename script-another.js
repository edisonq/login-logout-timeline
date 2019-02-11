const getXYPos = (data) => {
  const offset = $(data).offset();
  const xPos = offset.left;
  const yPos = offset.top;
  return {
    'xPos': xPos,
    'yPos' : yPos
  };
};

const getTimeByPos = (data, totalWidth, withZero = false) => {
  totalWidth -= 113;
  xPos = data.xPos
  //compute by percentage
  // 1am to 12pm
  // 11 hours is 100%
  // 660 minutes

  timeHours = Math.floor(((data.xPos/totalWidth)*660)/60);
  timeMinutes = Math.floor(((data.xPos/totalWidth)*660)%60);
  // add 1 because of 1am to 12pm
  timeHours++; 

  if (withZero) {
    if (timeHours   < 10) {timeHours   = `0${timeHours}`;}
    if (timeMinutes   < 10) {timeMinutes   = `0${timeMinutes}`;}
  }
  
  return `${timeHours}:${timeMinutes}`;         
};

$.fn.draggableXY = function(options) {
  const containmentX1 = $(".node").parent().offset().left;
  const containmentY1 = $(".node").parent().offset().top;
  const containmentX2 = ($(".node").parent().outerWidth() +  $(".node").parent().offset().left - $('.node').outerWidth());
  const containmentY2 = ($(".node").parent().outerHeight() +  $(".node").parent().offset().top - $('.node').outerHeight());
  const totalWidth = pagetotalWidth;
  
  const defaultOptions = {
    distance: 5,
    dynamic: false
  };

  options = $.extend(defaultOptions, options);
  
  this.draggable({

    distance: options.distance,
    containment:  [containmentX1, containmentY1, containmentX2, containmentY2],
    grid: [ 12, 28 ],
    start: function (event, ui) {
      ui.helper.data('draggableXY.originalPosition', ui.position || {top: 0, left: 0});
      ui.helper.data('draggableXY.newDrag', true);
    },
    stop: function(event, ui) {
      const xyPos = getXYPos(this);
      const xy = {
        'xPos': xyPos.xPos,
        'xPosPercentage': ((xyPos.xPos/totalWidth)*100),
        'yPos' : xyPos.yPos,
        'time' : getTimeByPos(xyPos,totalWidth),
        'totalWidth': totalWidth,
        'markerID': $(this).attr('id')
      };
      // console.log(JSON.stringify(xy));    
      if (getXYPos(this).yPos >= 272){
        $('#timelineEditor').addClass( "workflow-editor-in" );
        $(this).draggable( "option", "grid", [ 3, 6 ] );
      } else {
        $('#timelineEditor').removeClass( "workflow-editor-in" );
        $(this).draggable( "option", "grid", [  12, 28 ] );
      }  

      const domId = $(ui.helper[0]).attr('id');
      $(`#${domId}-time`).html(getTimeByPos(getXYPos(this),totalWidth,true));

      // sending new location in php
      $.post( "./php/updatexy.php", { "xyNumber": JSON.stringify(xy)} );
    },
    drag: function (event, ui) {
      const parent = $(this).attr('id');
      var originalPosition = ui.helper.data('draggableXY.originalPosition');
      var deltaX = Math.abs(originalPosition.left - ui.position.left);
      var deltaY = Math.abs(originalPosition.top - ui.position.top);      

      var newDrag = options.dynamic || ui.helper.data('draggableXY.newDrag');
      ui.helper.data('draggableXY.newDrag', false);

      var xMax = newDrag ? Math.max(deltaX, deltaY) === deltaX : ui.helper.data('draggableXY.xMax');
      ui.helper.data('draggableXY.xMax', xMax);
      
      // real time display
      const domId = $(ui.helper[0]).attr('id');
      $(`#${domId}-time`).html(getTimeByPos(getXYPos(this),totalWidth, true));
      
      if (getXYPos(this).yPos >= 272){
        $('#timelineEditor').addClass( "workflow-editor-in" );
        $(this).draggable( "option", "grid", [ 3, 6 ] );
      } else {
        $('#timelineEditor').removeClass( "workflow-editor-in" );
        $(this).draggable( "option", "grid", [  12, 28 ] );
      }
      

      var newPosition = ui.position;
      if(xMax) {
        newPosition.top = originalPosition.top;
      }
      if(!xMax){
        newPosition.left = originalPosition.left;
      }
      
      return newPosition;
    }
  });
};

$(document).ready(function(e) {
  $('#timelineEditor').click(function(e) {
      const askConfirm = confirm("Are you sure want to add marker?");
      const xyData = {
        'xPos': e.pageX,
        'yPos' : e.pageY
      }
      if (askConfirm == true) {
        const xy = {
          'xPos': e.pageX,
          'xPosPercentage': ((e.pageX/pagetotalWidth)*100),
          'yPos' : 244,
          'time' : getTimeByPos(xyData,pagetotalWidth),
          'totalWidth': pagetotalWidth,
          'markerID': `idmarker-${countMarker+1}-${markerType}`
        };
        // console.log(JSON.stringify(xy));      
  
        $.post( "./php/updatexy.php", { "xyNumber": JSON.stringify(xy)}, ()=>{location.reload();} );
        
      } else {
        
      }
  });
  $('.clear-marker').click(function(e) {
    const askConfirmClear = confirm("Are you sure want to clear?");
    if (askConfirmClear == true) {
      $.post( "./php/clearsession.php",{}, ()=>{location.reload();});
    }
  });
  $('.reload-page').click(function(e) {
        location.reload();
  });
});
$(window).resize(()=>{location.reload();});
