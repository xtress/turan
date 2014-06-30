// Garden Gnome Software - Skin
// Pano2VR 4.1.0/3405MS
// Filename: turan.ggsk
// Generated Вт 18. июн 20:45:38 2013

function pano2vrSkin(player,base) {
	var me=this;
	var flag=false;
	var nodeMarker=new Array();
	var activeNodeMarker=new Array();
	this.player=player;
	this.player.skinObj=this;
	this.divSkin=player.divSkin;
	var basePath="";
	// auto detect base path
	if (base=='?') {
		var scripts = document.getElementsByTagName('script');
		for(var i=0;i<scripts.length;i++) {
			var src=scripts[i].src;
			if (src.indexOf('skin.js')>=0) {
				var p=src.lastIndexOf('/');
				if (p>=0) {
					basePath=src.substr(0,p+1);
				}
			}
		}
	} else
	if (base) {
		basePath=base;
	}
	this.elementMouseDown=new Array();
	this.elementMouseOver=new Array();
	var cssPrefix='';
	var domTransition='transition';
	var domTransform='transform';
	var prefixes='Webkit,Moz,O,ms,Ms'.split(',');
	var i;
	for(i=0;i<prefixes.length;i++) {
		if (typeof document.body.style[prefixes[i] + 'Transform'] !== 'undefined') {
			cssPrefix='-' + prefixes[i].toLowerCase() + '-';
			domTransition=prefixes[i] + 'Transition';
			domTransform=prefixes[i] + 'Transform';
		}
	}
	
	this.player.setMargins(0,0,0,0);
	
	this.updateSize=function(startElement) {
		var stack=new Array();
		stack.push(startElement);
		while(stack.length>0) {
			e=stack.pop();
			if (e.ggUpdatePosition) {
				e.ggUpdatePosition();
			}
			if (e.hasChildNodes()) {
				for(i=0;i<e.childNodes.length;i++) {
					stack.push(e.childNodes[i]);
				}
			}
		}
	}
	
	parameterToTransform=function(p) {
		return 'translate(' + p.rx + 'px,' + p.ry + 'px) rotate(' + p.a + 'deg) scale(' + p.sx + ',' + p.sy + ')';
	}
	
	this.findElements=function(id,regex) {
		var r=new Array();
		var stack=new Array();
		var pat=new RegExp(id,'');
		stack.push(me.divSkin);
		while(stack.length>0) {
			e=stack.pop();
			if (regex) {
				if (pat.test(e.ggId)) r.push(e);
			} else {
				if (e.ggId==id) r.push(e);
			}
			if (e.hasChildNodes()) {
				for(i=0;i<e.childNodes.length;i++) {
					stack.push(e.childNodes[i]);
				}
			}
		}
		return r;
	}
	
	this.preloadImages=function() {
		var preLoadImg=new Image();
		preLoadImg.src=basePath + 'images/full_screen__o.png';
		preLoadImg.src=basePath + 'images/move_up__o.png';
		preLoadImg.src=basePath + 'images/move_down__o.png';
		preLoadImg.src=basePath + 'images/move_right__o.png';
		preLoadImg.src=basePath + 'images/move_left__o.png';
		preLoadImg.src=basePath + 'images/zoom_in__o.png';
		preLoadImg.src=basePath + 'images/zoom_out__o.png';
		preLoadImg.src=basePath + 'images/close1__o.png';
		preLoadImg.src=basePath + 'images/_1fx__o.png';
		preLoadImg.src=basePath + 'images/pizzax__o.png';
		preLoadImg.src=basePath + 'images/vipx__o.png';
		preLoadImg.src=basePath + 'images/close2__o.png';
		preLoadImg.src=basePath + 'images/_2fx__o.png';
		preLoadImg.src=basePath + 'images/restorantx__o.png';
		preLoadImg.src=basePath + 'images/banketx__o.png';
	}
	
	this.addSkin=function() {
		this._controller=document.createElement('div');
		this._controller.ggId='controller';
		this._controller.ggParameter={ rx:0,ry:0,a:0,sx:1,sy:1 };
		this._controller.ggVisible=true;
		this._controller.className='ggskin ggskin_container';
		this._controller.ggUpdatePosition=function() {
			this.style[domTransition]='none';
			if (this.parentNode) {
				w=this.parentNode.offsetWidth;
				this.style.left=(-186 + w/2) + 'px';
				h=this.parentNode.offsetHeight;
				this.style.top=(-74 + h) + 'px';
			}
		}
		hs ='position:absolute;';
		hs+='left: -186px;';
		hs+='top:  -74px;';
		hs+='width: 319px;';
		hs+='height: 96px;';
		hs+=cssPrefix + 'transform-origin: 50% 100%;';
		hs+='visibility: inherit;';
		this._controller.setAttribute('style',hs);
		this._full_screen=document.createElement('div');
		this._full_screen.ggId='full_screen';
		this._full_screen.ggParameter={ rx:0,ry:0,a:0,sx:1,sy:1 };
		this._full_screen.ggVisible=true;
		this._full_screen.className='ggskin ggskin_button';
		hs ='position:absolute;';
		hs+='left: 231px;';
		hs+='top:  0px;';
		hs+='width: 25px;';
		hs+='height: 24px;';
		hs+=cssPrefix + 'transform-origin: 50% 50%;';
		hs+='visibility: inherit;';
		hs+='cursor: pointer;';
		this._full_screen.setAttribute('style',hs);
		this._full_screen__img=document.createElement('img');
		this._full_screen__img.setAttribute('src',basePath + 'images/full_screen.png');
		this._full_screen__img.setAttribute('style','position: absolute;top: 0px;left: 0px;-webkit-user-drag:none;');
		this._full_screen__img['ondragstart']=function() { return false; };
		me.player.checkLoaded.push(this._full_screen__img);
		this._full_screen.appendChild(this._full_screen__img);
		this._full_screen.onclick=function () {
			me.player.toggleFullscreen();
		}
		this._full_screen.onmouseover=function () {
			if (me.player.transitionsDisabled) {
				me._full_screen.style[domTransition]='none';
			} else {
				me._full_screen.style[domTransition]='all 1000ms ease-out 0ms';
			}
			me._full_screen.ggParameter.rx=-10;me._full_screen.ggParameter.ry=10;
			me._full_screen.style[domTransform]=parameterToTransform(me._full_screen.ggParameter);
			me._full_screen__img.src=basePath + 'images/full_screen__o.png';
		}
		this._full_screen.onmouseout=function () {
			if (me.player.transitionsDisabled) {
				me._full_screen.style[domTransition]='none';
			} else {
				me._full_screen.style[domTransition]='all 1000ms ease-out 0ms';
			}
			me._full_screen.ggParameter.rx=0;me._full_screen.ggParameter.ry=0;
			me._full_screen.style[domTransform]=parameterToTransform(me._full_screen.ggParameter);
			me._full_screen__img.src=basePath + 'images/full_screen.png';
		}
		this._controller.appendChild(this._full_screen);
		this._move_up=document.createElement('div');
		this._move_up.ggId='move_up';
		this._move_up.ggParameter={ rx:0,ry:0,a:0,sx:1,sy:1 };
		this._move_up.ggVisible=true;
		this._move_up.className='ggskin ggskin_button';
		hs ='position:absolute;';
		hs+='left: 201px;';
		hs+='top:  0px;';
		hs+='width: 25px;';
		hs+='height: 24px;';
		hs+=cssPrefix + 'transform-origin: 50% 50%;';
		hs+='visibility: inherit;';
		hs+='cursor: pointer;';
		this._move_up.setAttribute('style',hs);
		this._move_up__img=document.createElement('img');
		this._move_up__img.setAttribute('src',basePath + 'images/move_up.png');
		this._move_up__img.setAttribute('style','position: absolute;top: 0px;left: 0px;-webkit-user-drag:none;');
		this._move_up__img['ondragstart']=function() { return false; };
		me.player.checkLoaded.push(this._move_up__img);
		this._move_up.appendChild(this._move_up__img);
		this._move_up.onmouseover=function () {
			if (me.player.transitionsDisabled) {
				me._move_up.style[domTransition]='none';
			} else {
				me._move_up.style[domTransition]='all 1000ms ease-out 0ms';
			}
			me._move_up.ggParameter.rx=-10;me._move_up.ggParameter.ry=10;
			me._move_up.style[domTransform]=parameterToTransform(me._move_up.ggParameter);
			me._move_up__img.src=basePath + 'images/move_up__o.png';
		}
		this._move_up.onmouseout=function () {
			if (me.player.transitionsDisabled) {
				me._move_up.style[domTransition]='none';
			} else {
				me._move_up.style[domTransition]='all 1000ms ease-out 0ms';
			}
			me._move_up.ggParameter.rx=0;me._move_up.ggParameter.ry=0;
			me._move_up.style[domTransform]=parameterToTransform(me._move_up.ggParameter);
			me._move_up__img.src=basePath + 'images/move_up.png';
			me.elementMouseDown['move_up']=false;
		}
		this._move_up.onmousedown=function () {
			me.elementMouseDown['move_up']=true;
		}
		this._move_up.onmouseup=function () {
			me.elementMouseDown['move_up']=false;
		}
		this._move_up.ontouchend=function () {
			me.elementMouseDown['move_up']=false;
		}
		this._controller.appendChild(this._move_up);
		this._move_down=document.createElement('div');
		this._move_down.ggId='move_down';
		this._move_down.ggParameter={ rx:0,ry:0,a:0,sx:1,sy:1 };
		this._move_down.ggVisible=true;
		this._move_down.className='ggskin ggskin_button';
		hs ='position:absolute;';
		hs+='left: 171px;';
		hs+='top:  0px;';
		hs+='width: 25px;';
		hs+='height: 24px;';
		hs+=cssPrefix + 'transform-origin: 50% 50%;';
		hs+='visibility: inherit;';
		hs+='cursor: pointer;';
		this._move_down.setAttribute('style',hs);
		this._move_down__img=document.createElement('img');
		this._move_down__img.setAttribute('src',basePath + 'images/move_down.png');
		this._move_down__img.setAttribute('style','position: absolute;top: 0px;left: 0px;-webkit-user-drag:none;');
		this._move_down__img['ondragstart']=function() { return false; };
		me.player.checkLoaded.push(this._move_down__img);
		this._move_down.appendChild(this._move_down__img);
		this._move_down.onmouseover=function () {
			if (me.player.transitionsDisabled) {
				me._move_down.style[domTransition]='none';
			} else {
				me._move_down.style[domTransition]='all 1000ms ease-out 0ms';
			}
			me._move_down.ggParameter.rx=-10;me._move_down.ggParameter.ry=10;
			me._move_down.style[domTransform]=parameterToTransform(me._move_down.ggParameter);
			me._move_down__img.src=basePath + 'images/move_down__o.png';
		}
		this._move_down.onmouseout=function () {
			if (me.player.transitionsDisabled) {
				me._move_down.style[domTransition]='none';
			} else {
				me._move_down.style[domTransition]='all 1000ms ease-out 0ms';
			}
			me._move_down.ggParameter.rx=0;me._move_down.ggParameter.ry=0;
			me._move_down.style[domTransform]=parameterToTransform(me._move_down.ggParameter);
			me._move_down__img.src=basePath + 'images/move_down.png';
			me.elementMouseDown['move_down']=false;
		}
		this._move_down.onmousedown=function () {
			me.elementMouseDown['move_down']=true;
		}
		this._move_down.onmouseup=function () {
			me.elementMouseDown['move_down']=false;
		}
		this._move_down.ontouchend=function () {
			me.elementMouseDown['move_down']=false;
		}
		this._controller.appendChild(this._move_down);
		this._move_right=document.createElement('div');
		this._move_right.ggId='move_right';
		this._move_right.ggParameter={ rx:0,ry:0,a:0,sx:1,sy:1 };
		this._move_right.ggVisible=true;
		this._move_right.className='ggskin ggskin_button';
		hs ='position:absolute;';
		hs+='left: 141px;';
		hs+='top:  0px;';
		hs+='width: 25px;';
		hs+='height: 24px;';
		hs+=cssPrefix + 'transform-origin: 50% 50%;';
		hs+='visibility: inherit;';
		hs+='cursor: pointer;';
		this._move_right.setAttribute('style',hs);
		this._move_right__img=document.createElement('img');
		this._move_right__img.setAttribute('src',basePath + 'images/move_right.png');
		this._move_right__img.setAttribute('style','position: absolute;top: 0px;left: 0px;-webkit-user-drag:none;');
		this._move_right__img['ondragstart']=function() { return false; };
		me.player.checkLoaded.push(this._move_right__img);
		this._move_right.appendChild(this._move_right__img);
		this._move_right.onmouseover=function () {
			if (me.player.transitionsDisabled) {
				me._move_right.style[domTransition]='none';
			} else {
				me._move_right.style[domTransition]='all 1000ms ease-out 0ms';
			}
			me._move_right.ggParameter.rx=-10;me._move_right.ggParameter.ry=10;
			me._move_right.style[domTransform]=parameterToTransform(me._move_right.ggParameter);
			me._move_right__img.src=basePath + 'images/move_right__o.png';
		}
		this._move_right.onmouseout=function () {
			if (me.player.transitionsDisabled) {
				me._move_right.style[domTransition]='none';
			} else {
				me._move_right.style[domTransition]='all 1000ms ease-out 0ms';
			}
			me._move_right.ggParameter.rx=0;me._move_right.ggParameter.ry=0;
			me._move_right.style[domTransform]=parameterToTransform(me._move_right.ggParameter);
			me._move_right__img.src=basePath + 'images/move_right.png';
			me.elementMouseDown['move_right']=false;
		}
		this._move_right.onmousedown=function () {
			me.elementMouseDown['move_right']=true;
		}
		this._move_right.onmouseup=function () {
			me.elementMouseDown['move_right']=false;
		}
		this._move_right.ontouchend=function () {
			me.elementMouseDown['move_right']=false;
		}
		this._controller.appendChild(this._move_right);
		this._move_left=document.createElement('div');
		this._move_left.ggId='move_left';
		this._move_left.ggParameter={ rx:0,ry:0,a:0,sx:1,sy:1 };
		this._move_left.ggVisible=true;
		this._move_left.className='ggskin ggskin_button';
		hs ='position:absolute;';
		hs+='left: 111px;';
		hs+='top:  0px;';
		hs+='width: 25px;';
		hs+='height: 24px;';
		hs+=cssPrefix + 'transform-origin: 50% 50%;';
		hs+='visibility: inherit;';
		hs+='cursor: pointer;';
		this._move_left.setAttribute('style',hs);
		this._move_left__img=document.createElement('img');
		this._move_left__img.setAttribute('src',basePath + 'images/move_left.png');
		this._move_left__img.setAttribute('style','position: absolute;top: 0px;left: 0px;-webkit-user-drag:none;');
		this._move_left__img['ondragstart']=function() { return false; };
		me.player.checkLoaded.push(this._move_left__img);
		this._move_left.appendChild(this._move_left__img);
		this._move_left.onmouseover=function () {
			if (me.player.transitionsDisabled) {
				me._move_left.style[domTransition]='none';
			} else {
				me._move_left.style[domTransition]='all 1000ms ease-out 0ms';
			}
			me._move_left.ggParameter.rx=-10;me._move_left.ggParameter.ry=10;
			me._move_left.style[domTransform]=parameterToTransform(me._move_left.ggParameter);
			me._move_left__img.src=basePath + 'images/move_left__o.png';
		}
		this._move_left.onmouseout=function () {
			if (me.player.transitionsDisabled) {
				me._move_left.style[domTransition]='none';
			} else {
				me._move_left.style[domTransition]='all 1000ms ease-out 0ms';
			}
			me._move_left.ggParameter.rx=0;me._move_left.ggParameter.ry=0;
			me._move_left.style[domTransform]=parameterToTransform(me._move_left.ggParameter);
			me._move_left__img.src=basePath + 'images/move_left.png';
			me.elementMouseDown['move_left']=false;
		}
		this._move_left.onmousedown=function () {
			me.elementMouseDown['move_left']=true;
		}
		this._move_left.onmouseup=function () {
			me.elementMouseDown['move_left']=false;
		}
		this._move_left.ontouchend=function () {
			me.elementMouseDown['move_left']=false;
		}
		this._controller.appendChild(this._move_left);
		this._zoom_in=document.createElement('div');
		this._zoom_in.ggId='zoom_in';
		this._zoom_in.ggParameter={ rx:0,ry:0,a:0,sx:1,sy:1 };
		this._zoom_in.ggVisible=true;
		this._zoom_in.className='ggskin ggskin_button';
		hs ='position:absolute;';
		hs+='left: 81px;';
		hs+='top:  0px;';
		hs+='width: 25px;';
		hs+='height: 24px;';
		hs+=cssPrefix + 'transform-origin: 50% 50%;';
		hs+='visibility: inherit;';
		hs+='cursor: pointer;';
		this._zoom_in.setAttribute('style',hs);
		this._zoom_in__img=document.createElement('img');
		this._zoom_in__img.setAttribute('src',basePath + 'images/zoom_in.png');
		this._zoom_in__img.setAttribute('style','position: absolute;top: 0px;left: 0px;-webkit-user-drag:none;');
		this._zoom_in__img['ondragstart']=function() { return false; };
		me.player.checkLoaded.push(this._zoom_in__img);
		this._zoom_in.appendChild(this._zoom_in__img);
		this._zoom_in.onmouseover=function () {
			if (me.player.transitionsDisabled) {
				me._zoom_in.style[domTransition]='none';
			} else {
				me._zoom_in.style[domTransition]='all 1000ms ease-out 0ms';
			}
			me._zoom_in.ggParameter.rx=-10;me._zoom_in.ggParameter.ry=10;
			me._zoom_in.style[domTransform]=parameterToTransform(me._zoom_in.ggParameter);
			me._zoom_in__img.src=basePath + 'images/zoom_in__o.png';
		}
		this._zoom_in.onmouseout=function () {
			if (me.player.transitionsDisabled) {
				me._zoom_in.style[domTransition]='none';
			} else {
				me._zoom_in.style[domTransition]='all 1000ms ease-out 0ms';
			}
			me._zoom_in.ggParameter.rx=0;me._zoom_in.ggParameter.ry=0;
			me._zoom_in.style[domTransform]=parameterToTransform(me._zoom_in.ggParameter);
			me._zoom_in__img.src=basePath + 'images/zoom_in.png';
			me.elementMouseDown['zoom_in']=false;
		}
		this._zoom_in.onmousedown=function () {
			me.elementMouseDown['zoom_in']=true;
		}
		this._zoom_in.onmouseup=function () {
			me.elementMouseDown['zoom_in']=false;
		}
		this._zoom_in.ontouchend=function () {
			me.elementMouseDown['zoom_in']=false;
		}
		this._controller.appendChild(this._zoom_in);
		this._zoom_out=document.createElement('div');
		this._zoom_out.ggId='zoom_out';
		this._zoom_out.ggParameter={ rx:0,ry:0,a:0,sx:1,sy:1 };
		this._zoom_out.ggVisible=true;
		this._zoom_out.className='ggskin ggskin_button';
		hs ='position:absolute;';
		hs+='left: 51px;';
		hs+='top:  0px;';
		hs+='width: 25px;';
		hs+='height: 24px;';
		hs+=cssPrefix + 'transform-origin: 50% 50%;';
		hs+='visibility: inherit;';
		hs+='cursor: pointer;';
		this._zoom_out.setAttribute('style',hs);
		this._zoom_out__img=document.createElement('img');
		this._zoom_out__img.setAttribute('src',basePath + 'images/zoom_out.png');
		this._zoom_out__img.setAttribute('style','position: absolute;top: 0px;left: 0px;-webkit-user-drag:none;');
		this._zoom_out__img['ondragstart']=function() { return false; };
		me.player.checkLoaded.push(this._zoom_out__img);
		this._zoom_out.appendChild(this._zoom_out__img);
		this._zoom_out.onmouseover=function () {
			if (me.player.transitionsDisabled) {
				me._zoom_out.style[domTransition]='none';
			} else {
				me._zoom_out.style[domTransition]='all 1000ms ease-out 0ms';
			}
			me._zoom_out.ggParameter.rx=-10;me._zoom_out.ggParameter.ry=10;
			me._zoom_out.style[domTransform]=parameterToTransform(me._zoom_out.ggParameter);
			me._zoom_out__img.src=basePath + 'images/zoom_out__o.png';
		}
		this._zoom_out.onmouseout=function () {
			if (me.player.transitionsDisabled) {
				me._zoom_out.style[domTransition]='none';
			} else {
				me._zoom_out.style[domTransition]='all 1000ms ease-out 0ms';
			}
			me._zoom_out.ggParameter.rx=0;me._zoom_out.ggParameter.ry=0;
			me._zoom_out.style[domTransform]=parameterToTransform(me._zoom_out.ggParameter);
			me._zoom_out__img.src=basePath + 'images/zoom_out.png';
			me.elementMouseDown['zoom_out']=false;
		}
		this._zoom_out.onmousedown=function () {
			me.elementMouseDown['zoom_out']=true;
		}
		this._zoom_out.onmouseup=function () {
			me.elementMouseDown['zoom_out']=false;
		}
		this._zoom_out.ontouchend=function () {
			me.elementMouseDown['zoom_out']=false;
		}
		this._controller.appendChild(this._zoom_out);
		this.divSkin.appendChild(this._controller);
		this._loading_text=document.createElement('div');
		this._loading_text.ggId='loading text';
		this._loading_text.ggParameter={ rx:0,ry:0,a:0,sx:1,sy:1 };
		this._loading_text.ggVisible=true;
		this._loading_text.className='ggskin ggskin_text';
		this._loading_text.ggUpdatePosition=function() {
			this.style[domTransition]='none';
			if (this.parentNode) {
				w=this.parentNode.offsetWidth;
				this.style.left=(-105 + w/2) + 'px';
				h=this.parentNode.offsetHeight;
				this.style.top=(-194 + h) + 'px';
			}
		}
		hs ='position:absolute;';
		hs+='left: -105px;';
		hs+='top:  -194px;';
		hs+='width: 169px;';
		hs+='height: 97px;';
		hs+=cssPrefix + 'transform-origin: 50% 100%;';
		hs+='opacity: 0.4;';
		hs+='visibility: inherit;';
		hs+='border: 0px solid #000000;';
		hs+='color: #5f3a6b;';
		hs+='text-align: center;';
		hs+='white-space: nowrap;';
		hs+='padding: 0px 1px 0px 1px;';
		hs+='overflow: hidden;';
		this._loading_text.setAttribute('style',hs);
		this._loading_text.ggUpdateText=function() {
			var hs="<font size=\"62\" face=\"Impact\" ><b> "+(me.player.getPercentLoaded()*100.0).toFixed(0)+"% <\/b><\/font>";
			if (hs!=this.ggText) {
				this.ggText=hs;
				this.innerHTML=hs;
			}
		}
		this._loading_text.ggUpdateText();
		this.divSkin.appendChild(this._loading_text);
		this._logo=document.createElement('div');
		this._logo.ggId='logo';
		this._logo.ggParameter={ rx:0,ry:0,a:0,sx:1,sy:1 };
		this._logo.ggVisible=true;
		this._logo.className='ggskin ggskin_image';
		hs ='position:absolute;';
		hs+='left: 12px;';
		hs+='top:  12px;';
		hs+='width: 200px;';
		hs+='height: 29px;';
		hs+=cssPrefix + 'transform-origin: 0% 0%;';
		hs+='visibility: inherit;';
		this._logo.setAttribute('style',hs);
		this._logo__img=document.createElement('img');
		this._logo__img.setAttribute('src',basePath + 'images/logo.png');
		this._logo__img.setAttribute('style','position: absolute;top: 0px;left: 0px;-webkit-user-drag:none;');
		this._logo__img['ondragstart']=function() { return false; };
		me.player.checkLoaded.push(this._logo__img);
		this._logo.appendChild(this._logo__img);
		this.divSkin.appendChild(this._logo);
		this._map1=document.createElement('div');
		this._map1.ggId='map1';
		this._map1.ggParameter={ rx:0,ry:0,a:0,sx:1,sy:1 };
		this._map1.ggVisible=true;
		this._map1.className='ggskin ggskin_image';
		this._map1.ggUpdatePosition=function() {
			this.style[domTransition]='none';
			if (this.parentNode) {
				w=this.parentNode.offsetWidth;
				this.style.left=(-255 + w) + 'px';
				h=this.parentNode.offsetHeight;
				this.style.top=(-254 + h) + 'px';
			}
		}
		hs ='position:absolute;';
		hs+='left: -255px;';
		hs+='top:  -254px;';
		hs+='width: 260px;';
		hs+='height: 160px;';
		hs+=cssPrefix + 'transform-origin: 100% 100%;';
		hs+='opacity: 0.7;';
		hs+='visibility: inherit;';
		this._map1.setAttribute('style',hs);
		this._map1__img=document.createElement('img');
		this._map1__img.setAttribute('src',basePath + 'images/map1.png');
		this._map1__img.setAttribute('style','position: absolute;top: 0px;left: 0px;-webkit-user-drag:none;');
		this._map1__img['ondragstart']=function() { return false; };
		me.player.checkLoaded.push(this._map1__img);
		this._map1.appendChild(this._map1__img);
		this._close1=document.createElement('div');
		this._close1.ggId='close1';
		this._close1.ggParameter={ rx:0,ry:0,a:0,sx:0.8,sy:0.8 };
		this._close1.ggVisible=true;
		this._close1.className='ggskin ggskin_button';
		hs ='position:absolute;';
		hs+='left: 213px;';
		hs+='top:  6px;';
		hs+='width: 25px;';
		hs+='height: 24px;';
		hs+=cssPrefix + 'transform-origin: 50% 50%;';
		hs+=cssPrefix + 'transform: ' + parameterToTransform(this._close1.ggParameter) + ';';
		hs+='visibility: inherit;';
		hs+='cursor: pointer;';
		this._close1.setAttribute('style',hs);
		this._close1__img=document.createElement('img');
		this._close1__img.setAttribute('src',basePath + 'images/close1.png');
		this._close1__img.setAttribute('style','position: absolute;top: 0px;left: 0px;-webkit-user-drag:none;');
		this._close1__img['ondragstart']=function() { return false; };
		me.player.checkLoaded.push(this._close1__img);
		this._close1.appendChild(this._close1__img);
		this._close1.onclick=function () {
			me._map1.style[domTransition]='none';
			me._map1.style.visibility='hidden';
			me._map1.ggVisible=false;
		}
		this._close1.onmouseover=function () {
			me._close1__img.src=basePath + 'images/close1__o.png';
		}
		this._close1.onmouseout=function () {
			me._close1__img.src=basePath + 'images/close1.png';
		}
		this._map1.appendChild(this._close1);
		this._bgp1=document.createElement('div');
		this._bgp1.ggId='bgp1';
		this._bgp1.ggParameter={ rx:0,ry:0,a:0,sx:1,sy:1 };
		this._bgp1.ggVisible=true;
		this._bgp1.className='ggskin ggskin_image';
		hs ='position:absolute;';
		hs+='left: 0px;';
		hs+='top:  0px;';
		hs+='width: 260px;';
		hs+='height: 160px;';
		hs+=cssPrefix + 'transform-origin: 50% 50%;';
		hs+='visibility: inherit;';
		this._bgp1.setAttribute('style',hs);
		this._bgp1__img=document.createElement('img');
		this._bgp1__img.setAttribute('src',basePath + 'images/bgp1.png');
		this._bgp1__img.setAttribute('style','position: absolute;top: 0px;left: 0px;-webkit-user-drag:none;');
		this._bgp1__img['ondragstart']=function() { return false; };
		me.player.checkLoaded.push(this._bgp1__img);
		this._bgp1.appendChild(this._bgp1__img);
		this._map1.appendChild(this._bgp1);
		this._bgp=document.createElement('div');
		this._bgp.ggId='bgp';
		this._bgp.ggParameter={ rx:0,ry:0,a:0,sx:1,sy:1 };
		this._bgp.ggVisible=true;
		this._bgp.className='ggskin ggskin_image';
		hs ='position:absolute;';
		hs+='left: 0px;';
		hs+='top:  0px;';
		hs+='width: 260px;';
		hs+='height: 160px;';
		hs+=cssPrefix + 'transform-origin: 50% 50%;';
		hs+='visibility: inherit;';
		this._bgp.setAttribute('style',hs);
		this._bgp__img=document.createElement('img');
		this._bgp__img.setAttribute('src',basePath + 'images/bgp.png');
		this._bgp__img.setAttribute('style','position: absolute;top: 0px;left: 0px;-webkit-user-drag:none;');
		this._bgp__img['ondragstart']=function() { return false; };
		me.player.checkLoaded.push(this._bgp__img);
		this._bgp.appendChild(this._bgp__img);
		this._map1.appendChild(this._bgp);
		this.__1fx=document.createElement('div');
		this.__1fx.ggId='1fx';
		this.__1fx.ggParameter={ rx:0,ry:0,a:0,sx:1,sy:1 };
		this.__1fx.ggVisible=true;
		this.__1fx.className='ggskin ggskin_button';
		hs ='position:absolute;';
		hs+='left: 120px;';
		hs+='top:  36px;';
		hs+='width: 32px;';
		hs+='height: 31px;';
		hs+=cssPrefix + 'transform-origin: 50% 50%;';
		hs+='visibility: inherit;';
		hs+='cursor: pointer;';
		this.__1fx.setAttribute('style',hs);
		this.__1fx__img=document.createElement('img');
		this.__1fx__img.setAttribute('src',basePath + 'images/_1fx.png');
		this.__1fx__img.setAttribute('style','position: absolute;top: 0px;left: 0px;-webkit-user-drag:none;');
		this.__1fx__img['ondragstart']=function() { return false; };
		me.player.checkLoaded.push(this.__1fx__img);
		this.__1fx.appendChild(this.__1fx__img);
		this.__1fx.onclick=function () {
			me.player.openUrl("foe1.swf","_self");
			me._bgb1.style[domTransition]='none';
			me._bgb1.style.visibility='hidden';
			me._bgb1.ggVisible=false;
			me._bgp1.style[domTransition]='none';
			me._bgp1.style.visibility='hidden';
			me._bgp1.ggVisible=false;
			me._bgr1.style[domTransition]='none';
			me._bgr1.style.visibility='hidden';
			me._bgr1.ggVisible=false;
		}
		this.__1fx.onmouseover=function () {
			me._label4.style[domTransition]='none';
			me._label4.style.visibility='inherit';
			me._label4.ggVisible=true;
			me.__1fx__img.src=basePath + 'images/_1fx__o.png';
		}
		this.__1fx.onmouseout=function () {
			me._label4.style[domTransition]='none';
			me._label4.style.visibility='hidden';
			me._label4.ggVisible=false;
			me.__1fx__img.src=basePath + 'images/_1fx.png';
		}
		this._label4=document.createElement('div');
		this._label4.ggId='label';
		this._label4.ggParameter={ rx:0,ry:0,a:0,sx:0.85,sy:0.85 };
		this._label4.ggVisible=false;
		this._label4.className='ggskin ggskin_text';
		this._label4.ggUpdatePosition=function() {
			this.style[domTransition]='none';
			this.style.left=(-48 + (112-this.offsetWidth)/2) + 'px';
		}
		hs ='position:absolute;';
		hs+='left: -48px;';
		hs+='top:  -21px;';
		hs+='width: auto;';
		hs+='height: auto;';
		hs+=cssPrefix + 'transform-origin: 50% 50%;';
		hs+=cssPrefix + 'transform: ' + parameterToTransform(this._label4.ggParameter) + ';';
		hs+='visibility: hidden;';
		hs+='background: #00006a;';
		hs+='border: 0px solid #000000;';
		hs+='color: #fffff9;';
		hs+='text-align: center;';
		hs+='white-space: nowrap;';
		hs+='padding: 0px 1px 0px 1px;';
		hs+='overflow: hidden;';
		this._label4.setAttribute('style',hs);
		this._label4.innerHTML="\u0424\u043e\u0439\u0435 1-\u0433\u043e \u044d\u0442\u0430\u0436\u0430";
		this.__1fx.appendChild(this._label4);
		this._map1.appendChild(this.__1fx);
		this._pizzax=document.createElement('div');
		this._pizzax.ggId='pizzax';
		this._pizzax.ggParameter={ rx:0,ry:0,a:0,sx:1,sy:1 };
		this._pizzax.ggVisible=true;
		this._pizzax.className='ggskin ggskin_button';
		hs ='position:absolute;';
		hs+='left: 183px;';
		hs+='top:  45px;';
		hs+='width: 32px;';
		hs+='height: 31px;';
		hs+=cssPrefix + 'transform-origin: 50% 50%;';
		hs+='visibility: inherit;';
		hs+='cursor: pointer;';
		this._pizzax.setAttribute('style',hs);
		this._pizzax__img=document.createElement('img');
		this._pizzax__img.setAttribute('src',basePath + 'images/pizzax.png');
		this._pizzax__img.setAttribute('style','position: absolute;top: 0px;left: 0px;-webkit-user-drag:none;');
		this._pizzax__img['ondragstart']=function() { return false; };
		me.player.checkLoaded.push(this._pizzax__img);
		this._pizzax.appendChild(this._pizzax__img);
		this._pizzax.onclick=function () {
			me.player.openUrl("pizza.swf","_self");
			me._bgp1.style[domTransition]='none';
			me._bgp1.style.visibility='inherit';
			me._bgp1.ggVisible=true;
			me._bgb1.style[domTransition]='none';
			me._bgb1.style.visibility='hidden';
			me._bgb1.ggVisible=false;
			me._bgr1.style[domTransition]='none';
			me._bgr1.style.visibility='hidden';
			me._bgr1.ggVisible=false;
		}
		this._pizzax.onmouseover=function () {
			me._label3.style[domTransition]='none';
			me._label3.style.visibility='inherit';
			me._label3.ggVisible=true;
			me._bgp.style[domTransition]='none';
			me._bgp.style.visibility='inherit';
			me._bgp.ggVisible=true;
			me._pizzax__img.src=basePath + 'images/pizzax__o.png';
		}
		this._pizzax.onmouseout=function () {
			me._label3.style[domTransition]='none';
			me._label3.style.visibility='hidden';
			me._label3.ggVisible=false;
			me._bgp.style[domTransition]='none';
			me._bgp.style.visibility='hidden';
			me._bgp.ggVisible=false;
			me._pizzax__img.src=basePath + 'images/pizzax.png';
		}
		this._label3=document.createElement('div');
		this._label3.ggId='label';
		this._label3.ggParameter={ rx:0,ry:0,a:0,sx:0.85,sy:0.85 };
		this._label3.ggVisible=false;
		this._label3.className='ggskin ggskin_text';
		this._label3.ggUpdatePosition=function() {
			this.style[domTransition]='none';
			this.style.left=(-30 + (82-this.offsetWidth)/2) + 'px';
		}
		hs ='position:absolute;';
		hs+='left: -30px;';
		hs+='top:  -21px;';
		hs+='width: auto;';
		hs+='height: auto;';
		hs+=cssPrefix + 'transform-origin: 50% 50%;';
		hs+=cssPrefix + 'transform: ' + parameterToTransform(this._label3.ggParameter) + ';';
		hs+='visibility: hidden;';
		hs+='background: #00006a;';
		hs+='border: 0px solid #000000;';
		hs+='color: #fffff9;';
		hs+='text-align: center;';
		hs+='white-space: nowrap;';
		hs+='padding: 0px 1px 0px 1px;';
		hs+='overflow: hidden;';
		this._label3.setAttribute('style',hs);
		this._label3.innerHTML="\u041f\u0438\u0446\u0446\u0435\u0440\u0438\u044f";
		this._pizzax.appendChild(this._label3);
		this._map1.appendChild(this._pizzax);
		this._vipx=document.createElement('div');
		this._vipx.ggId='vipx';
		this._vipx.ggParameter={ rx:0,ry:0,a:0,sx:1,sy:1 };
		this._vipx.ggVisible=true;
		this._vipx.className='ggskin ggskin_button';
		hs ='position:absolute;';
		hs+='left: 210px;';
		hs+='top:  108px;';
		hs+='width: 32px;';
		hs+='height: 31px;';
		hs+=cssPrefix + 'transform-origin: 50% 50%;';
		hs+='visibility: inherit;';
		hs+='cursor: pointer;';
		this._vipx.setAttribute('style',hs);
		this._vipx__img=document.createElement('img');
		this._vipx__img.setAttribute('src',basePath + 'images/vipx.png');
		this._vipx__img.setAttribute('style','position: absolute;top: 0px;left: 0px;-webkit-user-drag:none;');
		this._vipx__img['ondragstart']=function() { return false; };
		me.player.checkLoaded.push(this._vipx__img);
		this._vipx.appendChild(this._vipx__img);
		this._vipx.onclick=function () {
			me.player.openUrl("vip.swf","_self");
			me._bgp1.style[domTransition]='none';
			me._bgp1.style.visibility='inherit';
			me._bgp1.ggVisible=true;
			me._bgb1.style[domTransition]='none';
			me._bgb1.style.visibility='hidden';
			me._bgb1.ggVisible=false;
			me._bgr1.style[domTransition]='none';
			me._bgr1.style.visibility='hidden';
			me._bgr1.ggVisible=false;
		}
		this._vipx.onmouseover=function () {
			me._label2.style[domTransition]='none';
			me._label2.style.visibility='inherit';
			me._label2.ggVisible=true;
			me._bgp.style[domTransition]='none';
			me._bgp.style.visibility='inherit';
			me._bgp.ggVisible=true;
			me._vipx__img.src=basePath + 'images/vipx__o.png';
		}
		this._vipx.onmouseout=function () {
			me._label2.style[domTransition]='none';
			me._label2.style.visibility='hidden';
			me._label2.ggVisible=false;
			me._bgp.style[domTransition]='none';
			me._bgp.style.visibility='hidden';
			me._bgp.ggVisible=false;
			me._vipx__img.src=basePath + 'images/vipx.png';
		}
		this._label2=document.createElement('div');
		this._label2.ggId='label';
		this._label2.ggParameter={ rx:0,ry:0,a:0,sx:0.85,sy:0.85 };
		this._label2.ggVisible=false;
		this._label2.className='ggskin ggskin_text';
		this._label2.ggUpdatePosition=function() {
			this.style[domTransition]='none';
			this.style.left=(-18 + (55-this.offsetWidth)/2) + 'px';
		}
		hs ='position:absolute;';
		hs+='left: -18px;';
		hs+='top:  -15px;';
		hs+='width: auto;';
		hs+='height: auto;';
		hs+=cssPrefix + 'transform-origin: 50% 50%;';
		hs+=cssPrefix + 'transform: ' + parameterToTransform(this._label2.ggParameter) + ';';
		hs+='visibility: hidden;';
		hs+='background: #00006a;';
		hs+='border: 0px solid #000000;';
		hs+='color: #fffff9;';
		hs+='text-align: center;';
		hs+='white-space: nowrap;';
		hs+='padding: 0px 1px 0px 1px;';
		hs+='overflow: hidden;';
		this._label2.setAttribute('style',hs);
		this._label2.innerHTML="ViP \u0417\u043e\u043d\u0430";
		this._vipx.appendChild(this._label2);
		this._map1.appendChild(this._vipx);
		this.divSkin.appendChild(this._map1);
		this._map2=document.createElement('div');
		this._map2.ggId='map2';
		this._map2.ggParameter={ rx:0,ry:0,a:0,sx:1,sy:1 };
		this._map2.ggVisible=true;
		this._map2.className='ggskin ggskin_image';
		this._map2.ggUpdatePosition=function() {
			this.style[domTransition]='none';
			if (this.parentNode) {
				w=this.parentNode.offsetWidth;
				this.style.left=(-255 + w) + 'px';
				h=this.parentNode.offsetHeight;
				this.style.top=(-254 + h) + 'px';
			}
		}
		hs ='position:absolute;';
		hs+='left: -255px;';
		hs+='top:  -254px;';
		hs+='width: 260px;';
		hs+='height: 160px;';
		hs+=cssPrefix + 'transform-origin: 100% 100%;';
		hs+='opacity: 0.7;';
		hs+='visibility: inherit;';
		this._map2.setAttribute('style',hs);
		this._map2__img=document.createElement('img');
		this._map2__img.setAttribute('src',basePath + 'images/map2.png');
		this._map2__img.setAttribute('style','position: absolute;top: 0px;left: 0px;-webkit-user-drag:none;');
		this._map2__img['ondragstart']=function() { return false; };
		me.player.checkLoaded.push(this._map2__img);
		this._map2.appendChild(this._map2__img);
		this._close2=document.createElement('div');
		this._close2.ggId='close2';
		this._close2.ggParameter={ rx:0,ry:0,a:0,sx:0.8,sy:0.8 };
		this._close2.ggVisible=true;
		this._close2.className='ggskin ggskin_button';
		hs ='position:absolute;';
		hs+='left: 213px;';
		hs+='top:  6px;';
		hs+='width: 25px;';
		hs+='height: 24px;';
		hs+=cssPrefix + 'transform-origin: 50% 50%;';
		hs+=cssPrefix + 'transform: ' + parameterToTransform(this._close2.ggParameter) + ';';
		hs+='visibility: inherit;';
		hs+='cursor: pointer;';
		this._close2.setAttribute('style',hs);
		this._close2__img=document.createElement('img');
		this._close2__img.setAttribute('src',basePath + 'images/close2.png');
		this._close2__img.setAttribute('style','position: absolute;top: 0px;left: 0px;-webkit-user-drag:none;');
		this._close2__img['ondragstart']=function() { return false; };
		me.player.checkLoaded.push(this._close2__img);
		this._close2.appendChild(this._close2__img);
		this._close2.onclick=function () {
			me._map2.style[domTransition]='none';
			me._map2.style.visibility='hidden';
			me._map2.ggVisible=false;
		}
		this._close2.onmouseover=function () {
			me._close2__img.src=basePath + 'images/close2__o.png';
		}
		this._close2.onmouseout=function () {
			me._close2__img.src=basePath + 'images/close2.png';
		}
		this._map2.appendChild(this._close2);
		this._bgb1=document.createElement('div');
		this._bgb1.ggId='bgb1';
		this._bgb1.ggParameter={ rx:0,ry:0,a:0,sx:1,sy:1 };
		this._bgb1.ggVisible=true;
		this._bgb1.className='ggskin ggskin_image';
		hs ='position:absolute;';
		hs+='left: 0px;';
		hs+='top:  0px;';
		hs+='width: 260px;';
		hs+='height: 160px;';
		hs+=cssPrefix + 'transform-origin: 50% 50%;';
		hs+='visibility: inherit;';
		this._bgb1.setAttribute('style',hs);
		this._bgb1__img=document.createElement('img');
		this._bgb1__img.setAttribute('src',basePath + 'images/bgb1.png');
		this._bgb1__img.setAttribute('style','position: absolute;top: 0px;left: 0px;-webkit-user-drag:none;');
		this._bgb1__img['ondragstart']=function() { return false; };
		me.player.checkLoaded.push(this._bgb1__img);
		this._bgb1.appendChild(this._bgb1__img);
		this._map2.appendChild(this._bgb1);
		this._bgb=document.createElement('div');
		this._bgb.ggId='bgb';
		this._bgb.ggParameter={ rx:0,ry:0,a:0,sx:1,sy:1 };
		this._bgb.ggVisible=true;
		this._bgb.className='ggskin ggskin_image';
		hs ='position:absolute;';
		hs+='left: 0px;';
		hs+='top:  0px;';
		hs+='width: 260px;';
		hs+='height: 160px;';
		hs+=cssPrefix + 'transform-origin: 50% 50%;';
		hs+='visibility: inherit;';
		this._bgb.setAttribute('style',hs);
		this._bgb__img=document.createElement('img');
		this._bgb__img.setAttribute('src',basePath + 'images/bgb.png');
		this._bgb__img.setAttribute('style','position: absolute;top: 0px;left: 0px;-webkit-user-drag:none;');
		this._bgb__img['ondragstart']=function() { return false; };
		me.player.checkLoaded.push(this._bgb__img);
		this._bgb.appendChild(this._bgb__img);
		this._map2.appendChild(this._bgb);
		this._bgr1=document.createElement('div');
		this._bgr1.ggId='bgr1';
		this._bgr1.ggParameter={ rx:0,ry:0,a:0,sx:1,sy:1 };
		this._bgr1.ggVisible=true;
		this._bgr1.className='ggskin ggskin_image';
		hs ='position:absolute;';
		hs+='left: 0px;';
		hs+='top:  0px;';
		hs+='width: 260px;';
		hs+='height: 160px;';
		hs+=cssPrefix + 'transform-origin: 50% 50%;';
		hs+='visibility: inherit;';
		this._bgr1.setAttribute('style',hs);
		this._bgr1__img=document.createElement('img');
		this._bgr1__img.setAttribute('src',basePath + 'images/bgr1.png');
		this._bgr1__img.setAttribute('style','position: absolute;top: 0px;left: 0px;-webkit-user-drag:none;');
		this._bgr1__img['ondragstart']=function() { return false; };
		me.player.checkLoaded.push(this._bgr1__img);
		this._bgr1.appendChild(this._bgr1__img);
		this._map2.appendChild(this._bgr1);
		this._bgr=document.createElement('div');
		this._bgr.ggId='bgr';
		this._bgr.ggParameter={ rx:0,ry:0,a:0,sx:1,sy:1 };
		this._bgr.ggVisible=true;
		this._bgr.className='ggskin ggskin_image';
		hs ='position:absolute;';
		hs+='left: 0px;';
		hs+='top:  0px;';
		hs+='width: 260px;';
		hs+='height: 160px;';
		hs+=cssPrefix + 'transform-origin: 50% 50%;';
		hs+='visibility: inherit;';
		this._bgr.setAttribute('style',hs);
		this._bgr__img=document.createElement('img');
		this._bgr__img.setAttribute('src',basePath + 'images/bgr.png');
		this._bgr__img.setAttribute('style','position: absolute;top: 0px;left: 0px;-webkit-user-drag:none;');
		this._bgr__img['ondragstart']=function() { return false; };
		me.player.checkLoaded.push(this._bgr__img);
		this._bgr.appendChild(this._bgr__img);
		this._map2.appendChild(this._bgr);
		this.__2fx=document.createElement('div');
		this.__2fx.ggId='2fx';
		this.__2fx.ggParameter={ rx:0,ry:0,a:0,sx:1,sy:1 };
		this.__2fx.ggVisible=true;
		this.__2fx.className='ggskin ggskin_button';
		hs ='position:absolute;';
		hs+='left: 117px;';
		hs+='top:  39px;';
		hs+='width: 32px;';
		hs+='height: 31px;';
		hs+=cssPrefix + 'transform-origin: 50% 50%;';
		hs+='visibility: inherit;';
		hs+='cursor: pointer;';
		this.__2fx.setAttribute('style',hs);
		this.__2fx__img=document.createElement('img');
		this.__2fx__img.setAttribute('src',basePath + 'images/_2fx.png');
		this.__2fx__img.setAttribute('style','position: absolute;top: 0px;left: 0px;-webkit-user-drag:none;');
		this.__2fx__img['ondragstart']=function() { return false; };
		me.player.checkLoaded.push(this.__2fx__img);
		this.__2fx.appendChild(this.__2fx__img);
		this.__2fx.onclick=function () {
			me.player.openUrl("foe2.swf","_self");
			me._bgb1.style[domTransition]='none';
			me._bgb1.style.visibility='hidden';
			me._bgb1.ggVisible=false;
			me._bgp1.style[domTransition]='none';
			me._bgp1.style.visibility='hidden';
			me._bgp1.ggVisible=false;
			me._bgr1.style[domTransition]='none';
			me._bgr1.style.visibility='hidden';
			me._bgr1.ggVisible=false;
		}
		this.__2fx.onmouseover=function () {
			me._label1.style[domTransition]='none';
			me._label1.style.visibility='inherit';
			me._label1.ggVisible=true;
			me.__2fx__img.src=basePath + 'images/_2fx__o.png';
		}
		this.__2fx.onmouseout=function () {
			me._label1.style[domTransition]='none';
			me._label1.style.visibility='hidden';
			me._label1.ggVisible=false;
			me.__2fx__img.src=basePath + 'images/_2fx.png';
		}
		this._label1=document.createElement('div');
		this._label1.ggId='label';
		this._label1.ggParameter={ rx:0,ry:0,a:0,sx:0.85,sy:0.85 };
		this._label1.ggVisible=false;
		this._label1.className='ggskin ggskin_text';
		this._label1.ggUpdatePosition=function() {
			this.style[domTransition]='none';
			this.style.left=(-45 + (112-this.offsetWidth)/2) + 'px';
		}
		hs ='position:absolute;';
		hs+='left: -45px;';
		hs+='top:  -18px;';
		hs+='width: auto;';
		hs+='height: auto;';
		hs+=cssPrefix + 'transform-origin: 50% 50%;';
		hs+=cssPrefix + 'transform: ' + parameterToTransform(this._label1.ggParameter) + ';';
		hs+='visibility: hidden;';
		hs+='background: #00006a;';
		hs+='border: 0px solid #000000;';
		hs+='color: #fffff9;';
		hs+='text-align: center;';
		hs+='white-space: nowrap;';
		hs+='padding: 0px 1px 0px 1px;';
		hs+='overflow: hidden;';
		this._label1.setAttribute('style',hs);
		this._label1.innerHTML="\u0424\u043e\u0439\u0435 2-\u0433\u043e \u044d\u0442\u0430\u0436\u0430";
		this.__2fx.appendChild(this._label1);
		this._map2.appendChild(this.__2fx);
		this._restorantx=document.createElement('div');
		this._restorantx.ggId='restorantx';
		this._restorantx.ggParameter={ rx:0,ry:0,a:0,sx:1,sy:1 };
		this._restorantx.ggVisible=true;
		this._restorantx.className='ggskin ggskin_button';
		hs ='position:absolute;';
		hs+='left: 183px;';
		hs+='top:  51px;';
		hs+='width: 32px;';
		hs+='height: 31px;';
		hs+=cssPrefix + 'transform-origin: 50% 50%;';
		hs+='visibility: inherit;';
		hs+='cursor: pointer;';
		this._restorantx.setAttribute('style',hs);
		this._restorantx__img=document.createElement('img');
		this._restorantx__img.setAttribute('src',basePath + 'images/restorantx.png');
		this._restorantx__img.setAttribute('style','position: absolute;top: 0px;left: 0px;-webkit-user-drag:none;');
		this._restorantx__img['ondragstart']=function() { return false; };
		me.player.checkLoaded.push(this._restorantx__img);
		this._restorantx.appendChild(this._restorantx__img);
		this._restorantx.onclick=function () {
			me.player.openUrl("restorant.swf","_self");
			me._bgr1.style[domTransition]='none';
			me._bgr1.style.visibility='inherit';
			me._bgr1.ggVisible=true;
			me._bgp1.style[domTransition]='none';
			me._bgp1.style.visibility='hidden';
			me._bgp1.ggVisible=false;
			me._bgb1.style[domTransition]='none';
			me._bgb1.style.visibility='hidden';
			me._bgb1.ggVisible=false;
		}
		this._restorantx.onmouseover=function () {
			me._label0.style[domTransition]='none';
			me._label0.style.visibility='inherit';
			me._label0.ggVisible=true;
			me._bgr.style[domTransition]='none';
			me._bgr.style.visibility='inherit';
			me._bgr.ggVisible=true;
			me._restorantx__img.src=basePath + 'images/restorantx__o.png';
		}
		this._restorantx.onmouseout=function () {
			me._label0.style[domTransition]='none';
			me._label0.style.visibility='hidden';
			me._label0.ggVisible=false;
			me._bgr.style[domTransition]='none';
			me._bgr.style.visibility='hidden';
			me._bgr.ggVisible=false;
			me._restorantx__img.src=basePath + 'images/restorantx.png';
		}
		this._label0=document.createElement('div');
		this._label0.ggId='label';
		this._label0.ggParameter={ rx:0,ry:0,a:0,sx:0.85,sy:0.85 };
		this._label0.ggVisible=false;
		this._label0.className='ggskin ggskin_text';
		this._label0.ggUpdatePosition=function() {
			this.style[domTransition]='none';
			this.style.left=(-30 + (77-this.offsetWidth)/2) + 'px';
		}
		hs ='position:absolute;';
		hs+='left: -30px;';
		hs+='top:  -18px;';
		hs+='width: auto;';
		hs+='height: auto;';
		hs+=cssPrefix + 'transform-origin: 50% 50%;';
		hs+=cssPrefix + 'transform: ' + parameterToTransform(this._label0.ggParameter) + ';';
		hs+='visibility: hidden;';
		hs+='background: #00006a;';
		hs+='border: 0px solid #000000;';
		hs+='color: #fffff9;';
		hs+='text-align: center;';
		hs+='white-space: nowrap;';
		hs+='padding: 0px 1px 0px 1px;';
		hs+='overflow: hidden;';
		this._label0.setAttribute('style',hs);
		this._label0.innerHTML="\u0420\u0435\u0441\u0442\u043e\u0440\u0430\u043d";
		this._restorantx.appendChild(this._label0);
		this._map2.appendChild(this._restorantx);
		this._banketx=document.createElement('div');
		this._banketx.ggId='banketx';
		this._banketx.ggParameter={ rx:0,ry:0,a:0,sx:1,sy:1 };
		this._banketx.ggVisible=true;
		this._banketx.className='ggskin ggskin_button';
		hs ='position:absolute;';
		hs+='left: 39px;';
		hs+='top:  57px;';
		hs+='width: 32px;';
		hs+='height: 31px;';
		hs+=cssPrefix + 'transform-origin: 50% 50%;';
		hs+='visibility: inherit;';
		hs+='cursor: pointer;';
		this._banketx.setAttribute('style',hs);
		this._banketx__img=document.createElement('img');
		this._banketx__img.setAttribute('src',basePath + 'images/banketx.png');
		this._banketx__img.setAttribute('style','position: absolute;top: 0px;left: 0px;-webkit-user-drag:none;');
		this._banketx__img['ondragstart']=function() { return false; };
		me.player.checkLoaded.push(this._banketx__img);
		this._banketx.appendChild(this._banketx__img);
		this._banketx.onclick=function () {
			me.player.openUrl("banket.swf","_self");
			me._bgb1.style[domTransition]='none';
			me._bgb1.style.visibility='inherit';
			me._bgb1.ggVisible=true;
			me._bgp1.style[domTransition]='none';
			me._bgp1.style.visibility='hidden';
			me._bgp1.ggVisible=false;
			me._bgr1.style[domTransition]='none';
			me._bgr1.style.visibility='hidden';
			me._bgr1.ggVisible=false;
		}
		this._banketx.onmouseover=function () {
			me._label.style[domTransition]='none';
			me._label.style.visibility='inherit';
			me._label.ggVisible=true;
			me._bgb.style[domTransition]='none';
			me._bgb.style.visibility='inherit';
			me._bgb.ggVisible=true;
			me._banketx__img.src=basePath + 'images/banketx__o.png';
		}
		this._banketx.onmouseout=function () {
			me._label.style[domTransition]='none';
			me._label.style.visibility='hidden';
			me._label.ggVisible=false;
			me._bgb.style[domTransition]='none';
			me._bgb.style.visibility='hidden';
			me._bgb.ggVisible=false;
			me._banketx__img.src=basePath + 'images/banketx.png';
		}
		this._label=document.createElement('div');
		this._label.ggId='label';
		this._label.ggParameter={ rx:0,ry:0,a:0,sx:0.85,sy:0.85 };
		this._label.ggVisible=false;
		this._label.className='ggskin ggskin_text';
		this._label.ggUpdatePosition=function() {
			this.style[domTransition]='none';
			this.style.left=(-42 + (108-this.offsetWidth)/2) + 'px';
		}
		hs ='position:absolute;';
		hs+='left: -42px;';
		hs+='top:  -18px;';
		hs+='width: auto;';
		hs+='height: auto;';
		hs+=cssPrefix + 'transform-origin: 50% 50%;';
		hs+=cssPrefix + 'transform: ' + parameterToTransform(this._label.ggParameter) + ';';
		hs+='visibility: hidden;';
		hs+='background: #00006a;';
		hs+='border: 0px solid #000000;';
		hs+='color: #fffff9;';
		hs+='text-align: center;';
		hs+='white-space: nowrap;';
		hs+='padding: 0px 1px 0px 1px;';
		hs+='overflow: hidden;';
		this._label.setAttribute('style',hs);
		this._label.innerHTML="\u0411\u0430\u043d\u043a\u0435\u0442\u043d\u044b\u0439 \u0417\u0430\u043b";
		this._banketx.appendChild(this._label);
		this._map2.appendChild(this._banketx);
		this.divSkin.appendChild(this._map2);
		this._flor1=document.createElement('div');
		this._flor1.ggId='flor1';
		this._flor1.ggParameter={ rx:0,ry:0,a:0,sx:1,sy:1 };
		this._flor1.ggVisible=true;
		this._flor1.className='ggskin ggskin_button';
		this._flor1.ggUpdatePosition=function() {
			this.style[domTransition]='none';
			if (this.parentNode) {
				w=this.parentNode.offsetWidth;
				this.style.left=(-195 + w) + 'px';
				h=this.parentNode.offsetHeight;
				this.style.top=(-95 + h) + 'px';
			}
		}
		hs ='position:absolute;';
		hs+='left: -195px;';
		hs+='top:  -95px;';
		hs+='width: 195px;';
		hs+='height: 48px;';
		hs+=cssPrefix + 'transform-origin: 100% 100%;';
		hs+='visibility: inherit;';
		hs+='cursor: pointer;';
		this._flor1.setAttribute('style',hs);
		this._flor1__img=document.createElement('img');
		this._flor1__img.setAttribute('src',basePath + 'images/flor1.png');
		this._flor1__img.setAttribute('style','position: absolute;top: 0px;left: 0px;-webkit-user-drag:none;');
		this._flor1__img['ondragstart']=function() { return false; };
		me.player.checkLoaded.push(this._flor1__img);
		this._flor1.appendChild(this._flor1__img);
		this._flor1.onclick=function () {
			me._map1.style[domTransition]='none';
			me._map1.style.visibility='inherit';
			me._map1.ggVisible=true;
			me._map2.style[domTransition]='none';
			me._map2.style.visibility='hidden';
			me._map2.ggVisible=false;
		}
		this._flor1.onmouseover=function () {
			if (me.player.transitionsDisabled) {
				me._flor1.style[domTransition]='none';
			} else {
				me._flor1.style[domTransition]='all 500ms ease-out 0ms';
			}
			me._flor1.ggParameter.sx=1.05;me._flor1.ggParameter.sy=1.05;
			me._flor1.style[domTransform]=parameterToTransform(me._flor1.ggParameter);
		}
		this._flor1.onmouseout=function () {
			if (me.player.transitionsDisabled) {
				me._flor1.style[domTransition]='none';
			} else {
				me._flor1.style[domTransition]='all 500ms ease-out 0ms';
			}
			me._flor1.ggParameter.sx=1;me._flor1.ggParameter.sy=1;
			me._flor1.style[domTransform]=parameterToTransform(me._flor1.ggParameter);
		}
		this.divSkin.appendChild(this._flor1);
		this._flor2=document.createElement('div');
		this._flor2.ggId='flor2';
		this._flor2.ggParameter={ rx:0,ry:0,a:0,sx:1,sy:1 };
		this._flor2.ggVisible=true;
		this._flor2.className='ggskin ggskin_button';
		this._flor2.ggUpdatePosition=function() {
			this.style[domTransition]='none';
			if (this.parentNode) {
				w=this.parentNode.offsetWidth;
				this.style.left=(-195 + w) + 'px';
				h=this.parentNode.offsetHeight;
				this.style.top=(-50 + h) + 'px';
			}
		}
		hs ='position:absolute;';
		hs+='left: -195px;';
		hs+='top:  -50px;';
		hs+='width: 194px;';
		hs+='height: 48px;';
		hs+=cssPrefix + 'transform-origin: 100% 100%;';
		hs+='visibility: inherit;';
		hs+='cursor: pointer;';
		this._flor2.setAttribute('style',hs);
		this._flor2__img=document.createElement('img');
		this._flor2__img.setAttribute('src',basePath + 'images/flor2.png');
		this._flor2__img.setAttribute('style','position: absolute;top: 0px;left: 0px;-webkit-user-drag:none;');
		this._flor2__img['ondragstart']=function() { return false; };
		me.player.checkLoaded.push(this._flor2__img);
		this._flor2.appendChild(this._flor2__img);
		this._flor2.onclick=function () {
			me._map2.style[domTransition]='none';
			me._map2.style.visibility='inherit';
			me._map2.ggVisible=true;
			me._map1.style[domTransition]='none';
			me._map1.style.visibility='hidden';
			me._map1.ggVisible=false;
		}
		this._flor2.onmouseover=function () {
			if (me.player.transitionsDisabled) {
				me._flor2.style[domTransition]='none';
			} else {
				me._flor2.style[domTransition]='all 500ms ease-out 0ms';
			}
			me._flor2.ggParameter.sx=1.05;me._flor2.ggParameter.sy=1.05;
			me._flor2.style[domTransform]=parameterToTransform(me._flor2.ggParameter);
		}
		this._flor2.onmouseout=function () {
			if (me.player.transitionsDisabled) {
				me._flor2.style[domTransition]='none';
			} else {
				me._flor2.style[domTransition]='all 500ms ease-out 0ms';
			}
			me._flor2.ggParameter.sx=1;me._flor2.ggParameter.sy=1;
			me._flor2.style[domTransform]=parameterToTransform(me._flor2.ggParameter);
		}
		this.divSkin.appendChild(this._flor2);
		me._map1.style[domTransition]='none';
		me._map1.style.visibility='hidden';
		me._map1.ggVisible=false;
		me._bgp1.style[domTransition]='none';
		me._bgp1.style.visibility='hidden';
		me._bgp1.ggVisible=false;
		me._bgp.style[domTransition]='none';
		me._bgp.style.visibility='hidden';
		me._bgp.ggVisible=false;
		me._map2.style[domTransition]='none';
		me._map2.style.visibility='hidden';
		me._map2.ggVisible=false;
		me._bgb1.style[domTransition]='none';
		me._bgb1.style.visibility='hidden';
		me._bgb1.ggVisible=false;
		me._bgb.style[domTransition]='none';
		me._bgb.style.visibility='hidden';
		me._bgb.ggVisible=false;
		me._bgr1.style[domTransition]='none';
		me._bgr1.style.visibility='hidden';
		me._bgr1.ggVisible=false;
		me._bgr.style[domTransition]='none';
		me._bgr.style.visibility='hidden';
		me._bgr.ggVisible=false;
		this.preloadImages();
		this.divSkin.ggUpdateSize=function(w,h) {
			me.updateSize(me.divSkin);
		}
		this.divSkin.ggViewerInit=function() {
		}
		this.divSkin.ggLoaded=function() {
			me._loading_text.style[domTransition]='none';
			me._loading_text.style.visibility='hidden';
			me._loading_text.ggVisible=false;
		}
		this.divSkin.ggReLoaded=function() {
			me._loading_text.style[domTransition]='none';
			me._loading_text.style.visibility='inherit';
			me._loading_text.ggVisible=true;
		}
		this.divSkin.ggEnterFullscreen=function() {
		}
		this.divSkin.ggExitFullscreen=function() {
		}
		this.skinTimerEvent();
	};
	this.hotspotProxyClick=function(id) {
		if (id=='1f') {
			me.__1fx.onclick();
		}
		if (id=='pizza') {
			me._pizzax.onclick();
		}
		if (id=='vip') {
			me._vipx.onclick();
		}
		if (id=='2f') {
			me.__2fx.onclick();
		}
		if (id=='restorant') {
			me._restorantx.onclick();
		}
		if (id=='banket') {
			me._banketx.onclick();
		}
	}
	this.hotspotProxyOver=function(id) {
		if (id=='1f') {
			me.__1fx.onmouseover();
		}
		if (id=='pizza') {
			me._pizzax.onmouseover();
		}
		if (id=='vip') {
			me._vipx.onmouseover();
		}
		if (id=='2f') {
			me.__2fx.onmouseover();
		}
		if (id=='restorant') {
			me._restorantx.onmouseover();
		}
		if (id=='banket') {
			me._banketx.onmouseover();
		}
	}
	this.hotspotProxyOut=function(id) {
		if (id=='1f') {
			me.__1fx.onmouseout();
		}
		if (id=='pizza') {
			me._pizzax.onmouseout();
		}
		if (id=='vip') {
			me._vipx.onmouseout();
		}
		if (id=='2f') {
			me.__2fx.onmouseout();
		}
		if (id=='restorant') {
			me._restorantx.onmouseout();
		}
		if (id=='banket') {
			me._banketx.onmouseout();
		}
	}
	this.changeActiveNode=function(id) {
		var newMarker=new Array();
		var i,j;
		var tags=me.player.userdata.tags;
		for (i=0;i<nodeMarker.length;i++) {
			var match=false;
			if (nodeMarker[i].ggMarkerNodeId==id) match=true;
			for(j=0;j<tags.length;j++) {
				if (nodeMarker[i].ggMarkerNodeId==tags[j]) match=true;
			}
			if (match) {
				newMarker.push(nodeMarker[i]);
			}
		}
		for(i=0;i<activeNodeMarker.length;i++) {
			if (newMarker.indexOf(activeNodeMarker[i])<0) {
				if (activeNodeMarker[i].ggMarkerNormal) {
					activeNodeMarker[i].ggMarkerNormal.style.visibility='inherit';
				}
				if (activeNodeMarker[i].ggMarkerActive) {
					activeNodeMarker[i].ggMarkerActive.style.visibility='hidden';
				}
				if (activeNodeMarker[i].ggDeactivate) {
					activeNodeMarker[i].ggDeactivate();
				}
			}
		}
		for(i=0;i<newMarker.length;i++) {
			if (activeNodeMarker.indexOf(newMarker[i])<0) {
				if (newMarker[i].ggMarkerNormal) {
					newMarker[i].ggMarkerNormal.style.visibility='hidden';
				}
				if (newMarker[i].ggMarkerActive) {
					newMarker[i].ggMarkerActive.style.visibility='inherit';
				}
				if (newMarker[i].ggActivate) {
					newMarker[i].ggActivate();
				}
			}
		}
		activeNodeMarker=newMarker;
	}
	this.skinTimerEvent=function() {
		setTimeout(function() { me.skinTimerEvent(); }, 10);
		if (me.elementMouseDown['move_up']) {
			me.player.changeTilt(1,true);
		}
		if (me.elementMouseDown['move_down']) {
			me.player.changeTilt(-1,true);
		}
		if (me.elementMouseDown['move_right']) {
			me.player.changePan(-1.5,true);
		}
		if (me.elementMouseDown['move_left']) {
			me.player.changePan(1.5,true);
		}
		if (me.elementMouseDown['zoom_in']) {
			me.player.changeFovLog(-1,true);
		}
		if (me.elementMouseDown['zoom_out']) {
			me.player.changeFovLog(1,true);
		}
		this._loading_text.ggUpdateText();
	};
	function SkinHotspotClass(skinObj,hotspot) {
		var me=this;
		var flag=false;
		this.player=skinObj.player;
		this.skin=skinObj;
		this.hotspot=hotspot;
		this.elementMouseDown=new Array();
		this.elementMouseOver=new Array();
		this.__div=document.createElement('div');
		this.__div.setAttribute('style','position:absolute; left:0px;top:0px;visibility: inherit;');
		
		this.findElements=function(id,regex) {
			return me.skin.findElements(id,regex);
		}
		
		if (hotspot.skinid=='up') {
			this.__div=document.createElement('div');
			this.__div.ggId='up';
			this.__div.ggParameter={ rx:0,ry:0,a:0,sx:1,sy:1 };
			this.__div.ggVisible=true;
			this.__div.className='ggskin ggskin_hotspot';
			hs ='position:absolute;';
			hs+='left: 84px;';
			hs+='top:  294px;';
			hs+='width: 5px;';
			hs+='height: 5px;';
			hs+=cssPrefix + 'transform-origin: 50% 50%;';
			hs+='visibility: inherit;';
			this.__div.setAttribute('style',hs);
			this.__div.onclick=function () {
				me.skin.hotspotProxyClick(me.hotspot.id);
			}
			this.__div.onmouseover=function () {
				me.player.hotspot=me.hotspot;
				me.skin.hotspotProxyOver(me.hotspot.id);
			}
			this.__div.onmouseout=function () {
				me.player.hotspot=me.player.emptyHotspot;
				me.skin.hotspotProxyOut(me.hotspot.id);
			}
			this._up0=document.createElement('div');
			this._up0.ggId='up';
			this._up0.ggParameter={ rx:0,ry:0,a:0,sx:1,sy:1 };
			this._up0.ggVisible=true;
			this._up0.className='ggskin ggskin_button';
			hs ='position:absolute;';
			hs+='left: -30px;';
			hs+='top:  -18px;';
			hs+='width: 60px;';
			hs+='height: 54px;';
			hs+=cssPrefix + 'transform-origin: 50% 50%;';
			hs+='opacity: 0.8;';
			hs+='visibility: inherit;';
			hs+='cursor: pointer;';
			this._up0.setAttribute('style',hs);
			this._up0__img=document.createElement('img');
			this._up0__img.setAttribute('src',basePath + 'images/up0.png');
			this._up0__img.setAttribute('style','position: absolute;top: 0px;left: 0px;-webkit-user-drag:none;');
			this._up0__img['ondragstart']=function() { return false; };
			me.player.checkLoaded.push(this._up0__img);
			this._up0.appendChild(this._up0__img);
			this._up0.onclick=function () {
				me.player.openUrl(me.hotspot.url,me.hotspot.target);
				me.skin._map1.style[domTransition]='none';
				me.skin._map1.style.visibility='hidden';
				me.skin._map1.ggVisible=false;
				me.skin._map2.style[domTransition]='none';
				me.skin._map2.style.visibility='hidden';
				me.skin._map2.ggVisible=false;
			}
			this._up0.onmouseover=function () {
				if (me.player.transitionsDisabled) {
					me._up0.style[domTransition]='none';
				} else {
					me._up0.style[domTransition]='all 500ms ease-out 0ms';
				}
				me._up0.style.opacity='1';
				me._up0.style.visibility=me._up0.ggVisible?'inherit':'hidden';
				me._txt2.style[domTransition]='none';
				me._txt2.style.visibility='inherit';
				me._txt2.ggVisible=true;
			}
			this._up0.onmouseout=function () {
				if (me.player.transitionsDisabled) {
					me._up0.style[domTransition]='none';
				} else {
					me._up0.style[domTransition]='all 500ms ease-out 0ms';
				}
				me._up0.style.opacity='0.8';
				me._up0.style.visibility=me._up0.ggVisible?'inherit':'hidden';
				me._txt2.style[domTransition]='none';
				me._txt2.style.visibility='hidden';
				me._txt2.ggVisible=false;
			}
			this.__div.appendChild(this._up0);
			this._txt2=document.createElement('div');
			this._txt2.ggId='txt';
			this._txt2.ggParameter={ rx:0,ry:0,a:0,sx:1.2,sy:1.2 };
			this._txt2.ggVisible=false;
			this._txt2.className='ggskin ggskin_text';
			this._txt2.ggUpdatePosition=function() {
				this.style[domTransition]='none';
				this.style.left=(-51 + (97-this.offsetWidth)/2) + 'px';
			}
			hs ='position:absolute;';
			hs+='left: -51px;';
			hs+='top:  -36px;';
			hs+='width: auto;';
			hs+='height: auto;';
			hs+=cssPrefix + 'transform-origin: 50% 0%;';
			hs+=cssPrefix + 'transform: ' + parameterToTransform(this._txt2.ggParameter) + ';';
			hs+='opacity: 0.8;';
			hs+='visibility: hidden;';
			hs+='background: #2d0087;';
			hs+='border: 0px solid #d80000;';
			hs+='color: #ffffff;';
			hs+='text-align: center;';
			hs+='white-space: nowrap;';
			hs+='padding: 0px 1px 0px 1px;';
			hs+='overflow: hidden;';
			this._txt2.setAttribute('style',hs);
			this._txt2.innerHTML="|   "+me.hotspot.title+"   |";
			this.__div.appendChild(this._txt2);
		} else
		if (hotspot.skinid=='down') {
			this.__div=document.createElement('div');
			this.__div.ggId='down';
			this.__div.ggParameter={ rx:0,ry:0,a:0,sx:1,sy:1 };
			this.__div.ggVisible=true;
			this.__div.className='ggskin ggskin_hotspot';
			hs ='position:absolute;';
			hs+='left: 180px;';
			hs+='top:  294px;';
			hs+='width: 5px;';
			hs+='height: 5px;';
			hs+=cssPrefix + 'transform-origin: 50% 50%;';
			hs+='visibility: inherit;';
			this.__div.setAttribute('style',hs);
			this.__div.onclick=function () {
				me.skin.hotspotProxyClick(me.hotspot.id);
			}
			this.__div.onmouseover=function () {
				me.player.hotspot=me.hotspot;
				me.skin.hotspotProxyOver(me.hotspot.id);
			}
			this.__div.onmouseout=function () {
				me.player.hotspot=me.player.emptyHotspot;
				me.skin.hotspotProxyOut(me.hotspot.id);
			}
			this._down0=document.createElement('div');
			this._down0.ggId='down';
			this._down0.ggParameter={ rx:0,ry:0,a:0,sx:1,sy:1 };
			this._down0.ggVisible=true;
			this._down0.className='ggskin ggskin_button';
			hs ='position:absolute;';
			hs+='left: -30px;';
			hs+='top:  -15px;';
			hs+='width: 60px;';
			hs+='height: 54px;';
			hs+=cssPrefix + 'transform-origin: 50% 50%;';
			hs+='opacity: 0.8;';
			hs+='visibility: inherit;';
			hs+='cursor: pointer;';
			this._down0.setAttribute('style',hs);
			this._down0__img=document.createElement('img');
			this._down0__img.setAttribute('src',basePath + 'images/down0.png');
			this._down0__img.setAttribute('style','position: absolute;top: 0px;left: 0px;-webkit-user-drag:none;');
			this._down0__img['ondragstart']=function() { return false; };
			me.player.checkLoaded.push(this._down0__img);
			this._down0.appendChild(this._down0__img);
			this._down0.onclick=function () {
				me.player.openUrl(me.hotspot.url,me.hotspot.target);
				me.skin._map1.style[domTransition]='none';
				me.skin._map1.style.visibility='hidden';
				me.skin._map1.ggVisible=false;
				me.skin._map2.style[domTransition]='none';
				me.skin._map2.style.visibility='hidden';
				me.skin._map2.ggVisible=false;
			}
			this._down0.onmouseover=function () {
				if (me.player.transitionsDisabled) {
					me._down0.style[domTransition]='none';
				} else {
					me._down0.style[domTransition]='all 500ms ease-out 0ms';
				}
				me._down0.style.opacity='1';
				me._down0.style.visibility=me._down0.ggVisible?'inherit':'hidden';
				me._txt1.style[domTransition]='none';
				me._txt1.style.visibility='inherit';
				me._txt1.ggVisible=true;
			}
			this._down0.onmouseout=function () {
				if (me.player.transitionsDisabled) {
					me._down0.style[domTransition]='none';
				} else {
					me._down0.style[domTransition]='all 500ms ease-out 0ms';
				}
				me._down0.style.opacity='0.8';
				me._down0.style.visibility=me._down0.ggVisible?'inherit':'hidden';
				me._txt1.style[domTransition]='none';
				me._txt1.style.visibility='hidden';
				me._txt1.ggVisible=false;
			}
			this.__div.appendChild(this._down0);
			this._txt1=document.createElement('div');
			this._txt1.ggId='txt';
			this._txt1.ggParameter={ rx:0,ry:0,a:0,sx:1.2,sy:1.2 };
			this._txt1.ggVisible=false;
			this._txt1.className='ggskin ggskin_text';
			this._txt1.ggUpdatePosition=function() {
				this.style[domTransition]='none';
				this.style.left=(-48 + (97-this.offsetWidth)/2) + 'px';
			}
			hs ='position:absolute;';
			hs+='left: -48px;';
			hs+='top:  -39px;';
			hs+='width: auto;';
			hs+='height: auto;';
			hs+=cssPrefix + 'transform-origin: 50% 0%;';
			hs+=cssPrefix + 'transform: ' + parameterToTransform(this._txt1.ggParameter) + ';';
			hs+='opacity: 0.8;';
			hs+='visibility: hidden;';
			hs+='background: #2d0087;';
			hs+='border: 0px solid #d80000;';
			hs+='color: #ffffff;';
			hs+='text-align: center;';
			hs+='white-space: nowrap;';
			hs+='padding: 0px 1px 0px 1px;';
			hs+='overflow: hidden;';
			this._txt1.setAttribute('style',hs);
			this._txt1.innerHTML="|   "+me.hotspot.title+"   |";
			this.__div.appendChild(this._txt1);
		} else
		if (hotspot.skinid=='right') {
			this.__div=document.createElement('div');
			this.__div.ggId='right';
			this.__div.ggParameter={ rx:0,ry:0,a:0,sx:1,sy:1 };
			this.__div.ggVisible=true;
			this.__div.className='ggskin ggskin_hotspot';
			hs ='position:absolute;';
			hs+='left: 288px;';
			hs+='top:  297px;';
			hs+='width: 5px;';
			hs+='height: 5px;';
			hs+=cssPrefix + 'transform-origin: 50% 50%;';
			hs+='visibility: inherit;';
			this.__div.setAttribute('style',hs);
			this.__div.onclick=function () {
				me.skin.hotspotProxyClick(me.hotspot.id);
			}
			this.__div.onmouseover=function () {
				me.player.hotspot=me.hotspot;
				me.skin.hotspotProxyOver(me.hotspot.id);
			}
			this.__div.onmouseout=function () {
				me.player.hotspot=me.player.emptyHotspot;
				me.skin.hotspotProxyOut(me.hotspot.id);
			}
			this._right0=document.createElement('div');
			this._right0.ggId='right';
			this._right0.ggParameter={ rx:0,ry:0,a:0,sx:1,sy:1 };
			this._right0.ggVisible=true;
			this._right0.className='ggskin ggskin_button';
			hs ='position:absolute;';
			hs+='left: -30px;';
			hs+='top:  -27px;';
			hs+='width: 60px;';
			hs+='height: 54px;';
			hs+=cssPrefix + 'transform-origin: 50% 50%;';
			hs+='opacity: 0.8;';
			hs+='visibility: inherit;';
			hs+='cursor: pointer;';
			this._right0.setAttribute('style',hs);
			this._right0__img=document.createElement('img');
			this._right0__img.setAttribute('src',basePath + 'images/right0.png');
			this._right0__img.setAttribute('style','position: absolute;top: 0px;left: 0px;-webkit-user-drag:none;');
			this._right0__img['ondragstart']=function() { return false; };
			me.player.checkLoaded.push(this._right0__img);
			this._right0.appendChild(this._right0__img);
			this._right0.onclick=function () {
				me.player.openUrl(me.hotspot.url,me.hotspot.target);
				me.skin._map1.style[domTransition]='none';
				me.skin._map1.style.visibility='hidden';
				me.skin._map1.ggVisible=false;
				me.skin._map2.style[domTransition]='none';
				me.skin._map2.style.visibility='hidden';
				me.skin._map2.ggVisible=false;
			}
			this._right0.onmouseover=function () {
				if (me.player.transitionsDisabled) {
					me._right0.style[domTransition]='none';
				} else {
					me._right0.style[domTransition]='all 500ms ease-out 0ms';
				}
				me._right0.style.opacity='1';
				me._right0.style.visibility=me._right0.ggVisible?'inherit':'hidden';
				me._txt0.style[domTransition]='none';
				me._txt0.style.visibility='inherit';
				me._txt0.ggVisible=true;
			}
			this._right0.onmouseout=function () {
				if (me.player.transitionsDisabled) {
					me._right0.style[domTransition]='none';
				} else {
					me._right0.style[domTransition]='all 500ms ease-out 0ms';
				}
				me._right0.style.opacity='0.8';
				me._right0.style.visibility=me._right0.ggVisible?'inherit':'hidden';
				me._txt0.style[domTransition]='none';
				me._txt0.style.visibility='hidden';
				me._txt0.ggVisible=false;
			}
			this.__div.appendChild(this._right0);
			this._txt0=document.createElement('div');
			this._txt0.ggId='txt';
			this._txt0.ggParameter={ rx:0,ry:0,a:0,sx:1.2,sy:1.2 };
			this._txt0.ggVisible=false;
			this._txt0.className='ggskin ggskin_text';
			this._txt0.ggUpdatePosition=function() {
				this.style[domTransition]='none';
				this.style.left=(-51 + (97-this.offsetWidth)/2) + 'px';
			}
			hs ='position:absolute;';
			hs+='left: -51px;';
			hs+='top:  -45px;';
			hs+='width: auto;';
			hs+='height: auto;';
			hs+=cssPrefix + 'transform-origin: 50% 0%;';
			hs+=cssPrefix + 'transform: ' + parameterToTransform(this._txt0.ggParameter) + ';';
			hs+='opacity: 0.8;';
			hs+='visibility: hidden;';
			hs+='background: #2d0087;';
			hs+='border: 0px solid #d80000;';
			hs+='color: #ffffff;';
			hs+='text-align: center;';
			hs+='white-space: nowrap;';
			hs+='padding: 0px 1px 0px 1px;';
			hs+='overflow: hidden;';
			this._txt0.setAttribute('style',hs);
			this._txt0.innerHTML="|   "+me.hotspot.title+"   |";
			this.__div.appendChild(this._txt0);
		} else
		{
			this.__div=document.createElement('div');
			this.__div.ggId='left';
			this.__div.ggParameter={ rx:0,ry:0,a:0,sx:1,sy:1 };
			this.__div.ggVisible=true;
			this.__div.className='ggskin ggskin_hotspot';
			hs ='position:absolute;';
			hs+='left: 387px;';
			hs+='top:  297px;';
			hs+='width: 5px;';
			hs+='height: 5px;';
			hs+=cssPrefix + 'transform-origin: 50% 50%;';
			hs+='visibility: inherit;';
			this.__div.setAttribute('style',hs);
			this.__div.onclick=function () {
				me.skin.hotspotProxyClick(me.hotspot.id);
			}
			this.__div.onmouseover=function () {
				me.player.hotspot=me.hotspot;
				me.skin.hotspotProxyOver(me.hotspot.id);
			}
			this.__div.onmouseout=function () {
				me.player.hotspot=me.player.emptyHotspot;
				me.skin.hotspotProxyOut(me.hotspot.id);
			}
			this._left0=document.createElement('div');
			this._left0.ggId='left';
			this._left0.ggParameter={ rx:0,ry:0,a:0,sx:1,sy:1 };
			this._left0.ggVisible=true;
			this._left0.className='ggskin ggskin_button';
			hs ='position:absolute;';
			hs+='left: -36px;';
			hs+='top:  -24px;';
			hs+='width: 60px;';
			hs+='height: 54px;';
			hs+=cssPrefix + 'transform-origin: 50% 50%;';
			hs+='opacity: 0.8;';
			hs+='visibility: inherit;';
			hs+='cursor: pointer;';
			this._left0.setAttribute('style',hs);
			this._left0__img=document.createElement('img');
			this._left0__img.setAttribute('src',basePath + 'images/left0.png');
			this._left0__img.setAttribute('style','position: absolute;top: 0px;left: 0px;-webkit-user-drag:none;');
			this._left0__img['ondragstart']=function() { return false; };
			me.player.checkLoaded.push(this._left0__img);
			this._left0.appendChild(this._left0__img);
			this._left0.onclick=function () {
				me.player.openUrl(me.hotspot.url,me.hotspot.target);
				me.skin._map1.style[domTransition]='none';
				me.skin._map1.style.visibility='hidden';
				me.skin._map1.ggVisible=false;
				me.skin._map2.style[domTransition]='none';
				me.skin._map2.style.visibility='hidden';
				me.skin._map2.ggVisible=false;
			}
			this._left0.onmouseover=function () {
				if (me.player.transitionsDisabled) {
					me._left0.style[domTransition]='none';
				} else {
					me._left0.style[domTransition]='all 500ms ease-out 0ms';
				}
				me._left0.style.opacity='1';
				me._left0.style.visibility=me._left0.ggVisible?'inherit':'hidden';
				me._txt.style[domTransition]='none';
				me._txt.style.visibility='inherit';
				me._txt.ggVisible=true;
			}
			this._left0.onmouseout=function () {
				if (me.player.transitionsDisabled) {
					me._left0.style[domTransition]='none';
				} else {
					me._left0.style[domTransition]='all 500ms ease-out 0ms';
				}
				me._left0.style.opacity='0.8';
				me._left0.style.visibility=me._left0.ggVisible?'inherit':'hidden';
				me._txt.style[domTransition]='none';
				me._txt.style.visibility='hidden';
				me._txt.ggVisible=false;
			}
			this.__div.appendChild(this._left0);
			this._txt=document.createElement('div');
			this._txt.ggId='txt';
			this._txt.ggParameter={ rx:0,ry:0,a:0,sx:1.2,sy:1.2 };
			this._txt.ggVisible=false;
			this._txt.className='ggskin ggskin_text';
			this._txt.ggUpdatePosition=function() {
				this.style[domTransition]='none';
				this.style.left=(-51 + (97-this.offsetWidth)/2) + 'px';
			}
			hs ='position:absolute;';
			hs+='left: -51px;';
			hs+='top:  -45px;';
			hs+='width: auto;';
			hs+='height: auto;';
			hs+=cssPrefix + 'transform-origin: 50% 0%;';
			hs+=cssPrefix + 'transform: ' + parameterToTransform(this._txt.ggParameter) + ';';
			hs+='opacity: 0.8;';
			hs+='visibility: hidden;';
			hs+='background: #2d0087;';
			hs+='border: 0px solid #d80000;';
			hs+='color: #ffffff;';
			hs+='text-align: center;';
			hs+='white-space: nowrap;';
			hs+='padding: 0px 1px 0px 1px;';
			hs+='overflow: hidden;';
			this._txt.setAttribute('style',hs);
			this._txt.innerHTML="|   "+me.hotspot.title+"   |";
			this.__div.appendChild(this._txt);
		}
	};
	this.addSkinHotspot=function(hotspot) {
		return new SkinHotspotClass(me,hotspot);
	}
	this.addSkin();
};