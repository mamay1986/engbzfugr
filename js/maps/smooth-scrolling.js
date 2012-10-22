function makeScrollable(el, listener) {
  if (typeof el == 'string') el = document.getElementById(el)

  var ew, eh;

  // create our new container element
  var newElement = document.createElement('DIV');
  newElement.id = el.id + '-scroll-container';
  newElement.style.overflow = 'hidden';

  // if it was static before then just make sure things are the same size
  if (getStyle(el, 'position') == 'static') {
    newElement.style.position = 'relative'
    ew = el.clientWidth
    eh = el.clientHeight
  }
  else {
    newElement.style.position = getStyle(el, 'position');

    var top = getStyle(el, 'top')
      , bottom = getStyle(el, 'bottom')
      , left = getStyle(el, 'left')
      , right = getStyle(el, 'right')

    newElement.style.top = top;
    newElement.style.left = left;
    newElement.style.right = right;
    newElement.style.bottom = bottom;

    if (top == 'auto' || bottom == 'auto') newElement.style.height = getStyle(el, 'height');
    if (left == 'auto' || right == 'auto') newElement.style.width = getStyle(el, 'width');
  }

  // add our new element to the dom
  el.parentNode.insertBefore(newElement, el);

  // make sure the old element can be positioned properly in the new one
  el.style.position = 'absolute'
  el.style.right = 'auto'
  el.style.bottom = 'auto'

  // this is the div that will have the scroll bars.  It will be bigger
  // than the old element and live inside the old one.  The old element will
  // then use overflow to hide these scroll bars.  Our previous content
  // which is being mimicked will live inside this
  var scroller = document.createElement('div')
  scroller.style.overflow = 'scroll';
  scroller.style.width = '110%'
  scroller.style.height = '110%'
  scroller.style.position = 'absolute'
  scroller.style.top = '0'
  scroller.style.left = '0'

  // add a big element to the scroller element, this ensures that the scroller
  // element has scroll bars.
  var big = document.createElement('div')
  big.className = 'big';

  // add everything to the main element
  scroller.appendChild(big);
  scroller.appendChild(el);
  newElement.appendChild(scroller);

  // the default/main/normal scroll position of the scroller element.
  var def;

  resize(ew, eh);

  return {
      container: newElement
    , resize: resize
    // TODO add remove method?
    };

  function setScroll(x, y) {
    if (typeof x != 'undefined') def = {x: x, y: y};

    if (scroller.detachEvent)
      scroller.detachEvent('onscroll', scrollEvent)
    else {
      scroller.removeEventListener('mousewheel', scrollEvent, false)
      scroller.removeEventListener('MozMousePixelScroll', scrollEvent, false)
    }

    scroller.scrollLeft = def.x;
    scroller.scrollTop = def.y;

    el.style.left = def.x + 'px'
    el.style.top = def.y + 'px'

    setTimeout(function() {
      // depends on what browser event model we are using...
      if (scroller.attachEvent)
        scroller.attachEvent('onscroll', scrollEvent)
      else {
        scroller.addEventListener('scroll', scrollEvent, false)
      }
    }, 0);
  }

  function resize(ew, eh) {
    if (typeof ew != 'undefined') newElement.style.width = ew + 'px'
    if (typeof eh != 'undefined') newElement.style.height = eh + 'px'

    ew = newElement.clientWidth
    eh = newElement.clientHeight

    el.style.width = ew + 'px'
    el.style.height = eh + 'px'
    big.style.width = ew*5+'px'
    big.style.height = eh*5+'px'

    setScroll(ew*2, eh*2);
  }

  function scrollEvent(e) {
    // calculate what has changed
    var delta = {x: def.x - scroller.scrollLeft, y: def.y - scroller.scrollTop}

    // if nothing has changed, then do nothing
    if (delta.x == 0 && delta.y == 0) return

    // reset the scroller to the correct/main/normal position
    scroller.scrollLeft = def.x
    scroller.scrollTop = def.y

    // notify our listener
    listener(delta)
  }

  function getStyle(x,styleProp) {
    if (x.currentStyle)
      var y = x.currentStyle[styleProp];
    else if (window.getComputedStyle)
      var y = document.defaultView.getComputedStyle(x,null).getPropertyValue(styleProp);
    return y;
  }
}


