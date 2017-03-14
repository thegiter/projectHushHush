(function() {
	const HOOK_NAME = 'charts';
	
	var mM = shpsCmm.moduleMgr;
	
var clipSvgIdCnt = 0;

var replaceTransProp = function(style, pattern, newStr) {
	style.transition = style.transition.replace(pattern, newStr);
};

const TRANS_PROP = 'transform';
const CTR_TRANS_PROP = 'bottom';
const DEFAULT_TRANS = ' 1s ease-out';

var PieSlice = React.createClass({
			halfAmt: 0,
			transCss: TRANS_PROP+DEFAULT_TRANS,
			ctrTransCss: CTR_TRANS_PROP+DEFAULT_TRANS,
			startStyle: {
				transition: TRANS_PROP+DEFAULT_TRANS
			},
			wprStyle: {
				transition: TRANS_PROP+DEFAULT_TRANS+', '+CTR_TRANS_PROP+DEFAULT_TRANS
			},
			fillerAmtStyle: {
				transition: TRANS_PROP+DEFAULT_TRANS
			},
			amtStyle: {
				transition: TRANS_PROP+DEFAULT_TRANS
			},
			initCls: 'init',
			pointerEvtsCls: 'pointer-evts',
			clipSvg: '',
			getDefaultProps: function() {
				return {
					intro: true,
					clss: '',
					onClk: function() {},
					onMseEtr: function() {}
				}
			},
			intro: function() {
				//remove initCls
				this.cnr.classList.remove(this.initCls);
			},
			getStartTransformCss: function() {
				return 'rotate('+(this.halfAmt + this.props.startPercent)+'turn)';
			},
			updateStartTransform: function() {
				this.area.style.transform = this.getStartTransformCss();
			},
			setStartPercent: function(sp) {
				this.props.startPercent = sp;
				
				this.updateStartTransform();
			},
			getAmtTransformCss: function() {
				this.halfAmt = this.props.amtPercent / 2;
				
				return {
					wpr: 'rotate('+(-this.halfAmt)+'turn)',
					filler: 'rotate('+(this.props.amtPercent * 2 / 3)+'turn)',
					slice: 'rotate('+(this.halfAmt)+'turn)'
				};
			},
			setAmtPercent: function(ap) {
				this.props.amtPercent = ap;
				
				var css = this.getAmtTransformCss();
				
				this.wpr.style.transform = css.wpr;
				this.filler.style.transform = css.filler;
				
				this.slices.forEach(function(slice) {
					slice.style.transform = css.slice;
				});
				
				//also update startPercent, because start percent uses amt percent as well
				this.updateStartTransform();
			},
			setCtrDistance: function(css) {
				this.wpr.style.bottom = css;
			},
			getClipPathD: function(r) {
				var innerCircStartPoint = (1 - r) / 2;
				var innerCircRadius = r / 2;
				
				/*outter arc go clock wise, inner go counter clock wise (the third flag)
				this carves out the inner*/
				//either babel or uglyfly is not working well with this multiline format in return statement
				var d = 'M 0 .5\
				A .5 .5, 0, 1, 1, 1 .5\
				A .5 .5, 0, 1, 1, 0 .5\
				H '+innerCircStartPoint+'\
				A '+innerCircRadius+' '+innerCircRadius+', 0, 1, 0, '+(1 - innerCircStartPoint)+' .5\
				A '+innerCircRadius+' '+innerCircRadius+', 0, 1, 0, '+innerCircStartPoint+' .5\
				Z';
				
				return d;
			},
			setCtrHoleRadius: function(radius) {
				this.clipSvgPath.d = this.getClipPathD(radius);
			},
			replaceTrans: function(style, newStr) {
				replaceTransProp(style, this.transCss, newStr);
			},
			apiReplaceTrans: function(newStr) {
				var theThis = this;
				
				[
					this.area,
					this.wpr,
					this.filler
				].concat(this.slices).forEach(function(elm) {
					theThis.replaceTrans(elm.style, newStr);
				});
			},
			setTransTmpl: function(css, repFct) {
				var c = TRANS_PROP+' '+css;
					
				repFct(c);
				
				this.transCss = c;
			},
			setTrans: function(css) {
				this.setTransTmpl(css, this.apiReplaceTrans);
			},
			rmvTrans: function() {
				this.apiReplaceTrans('');
			},
			replaceCtrTrans: function(style, newStr) {
				replaceTransProp(style, this.ctrTransCss, newStr);
			},
			apiReplaceCtrTrans: function(newStr) {
				this.replaceCtrTrans(this.wpr.style, newStr);
			},
			setCtrTransTmpl: function(css, repFct) {
				var c = CTR_TRANS_PROP+' '+css;
					
				repFct(c);
				
				this.ctrTransCss = c;
			},
			setCtrTrans: function(css) {
				this.setCtrTransTmpl(css, this.apiReplaceCtrTrans);
			},
			rmvCtrTrans: function() {
				this.apiReplaceCtrTrans('');
			},
			componentDidMount: function() {
				if (this.props.intro) {
					this.cnr.offsetHeight;
				
					this.intro();
				}
			},
			render: function() {
				var theThis = this;
				
				function setStartTransform() {
					theThis.startStyle.transform = theThis.getStartTransformCss();
				}
				
				if (this.props.amtPercent) {
					var css = this.getAmtTransformCss();
					
					this.wprStyle.transform = css.wpr;
					this.fillerAmtStyle.transform = css.filler;
					this.amtStyle.transform = css.slice;
					
					setStartTransform();
				} else if (this.props.startPercent) {
					setStartTransform();
				}
				
				function renderReplaceTrans(newStr) {
					[
						theThis.startStyle,
						theThis.wprStyle,
						theThis.fillerAmtStyle,
						theThis.amtStyle
					].forEach(function(style) {
						theThis.replaceTrans(style, newStr);
					});
				}
				
				if (this.props.customTransCss) {
					this.setTransTmpl(this.props.customTransCss, renderReplaceTrans);
				} else if (this.props.defaultTrans === false) {
					renderReplaceTrans('');
				}
				
				if (this.props.initClosed === false) {
					this.props.intro = false;
					this.initCls = '';
				}
				
				if (this.props.pointerEvts === false) {
					this.pointerEvtsCls = '';
				}
				
				if (this.props.ctrDistance) {
					this.wprStyle.bottom = this.props.ctrDistance;
				}
				
				function renderReplaceCtrTrans(newStr) {
					theThis.replaceCtrTrans(theThis.wprStyle, newStr);
				}
				
				if (this.props.customCtrTransCss) {
					this.setCtrTransTmpl(this.props.customCtrTransCss, renderReplaceCtrTrans);
				} else if (this.props.defaultCtrTrans === false) {
					renderReplaceCtrTrans('');
				}
				
				if (this.props.ctrHoleRadius) {
					this.clipSvgId = 'pie-slice-clip-svg-'+clipSvgIdCnt++;
					
					this.clipSvg = <svg className="vsb-hid pos-abs w-0 h-0" xmlns="http://www.w3.org/2000/svg">
						<defs>
							<clipPath id={this.clipSvgId} clipPathUnits="objectBoundingBox">
								<path d={this.getClipPathD(this.props.ctrHoleRadius)}/>
							</clipPath>
						</defs>
					</svg>;
					
					this.wprStyle.clipPath = 'url(#'+this.clipSvgId+')';
				}
				
				//curly braces inside the jsx/html syntax is used to indicate javascript
				//so js comments must also be in curly braces
				//single quotes are also not valid in jsx/html, so you must use them in {}, too
				return (
					<div className={'pie-slice-cnr '+this.initCls+' '+this.pointerEvtsCls+' '+this.props.clss} ref={function(elm) {
						theThis.cnr = elm;
						theThis.area = elm.children[0];
						
						var wpr = theThis.wpr = elm.getElementsByClassName('wpr')[0];
						var btmHalf = wpr.children[0];
						var topHalf = wpr.children[1];
						
						theThis.filler = btmHalf.children[0];
						theThis.slices = [
							btmHalf.children[1],
							topHalf,
							topHalf.children[0]
						];
						
						if (theThis.clipSvgId) {
							theThis.clipSvgPath = document.getElementById(theThis.clipSvgId).children[0];
						}
						
						elm.reactObj = theThis;
					}}>{/*full circ*/}
						<div className={'slice-area'} style={this.startStyle}>
							{this.clipSvg}
							
							<div className={'wpr'} style={this.wprStyle} onClick={this.props.onClk} onMouseEnter={this.props.onMseEtr}>{/*wrapper*/}
								{/*top half and bottom half each rotate half the degrees
								in addition, the rotational also rotate half the degrees*/}
								<div>{/*half circ cnr, static*/}
									<div className={'slice'} style={this.fillerAmtStyle}>{/*filler half circ to fill the gap caused by aliasing*/}
									</div>
									<div className={'slice'} style={this.amtStyle}>{/*actual half circ*/}
									</div>
								</div>
								<div style={this.amtStyle}>{/*half circ cnr, rotational*/}
									<div className={'slice'} style={this.amtStyle}>{/*actual half circ*/}
									</div>
								</div>
							</div>
						</div>
					</div>
				);
			}
		});
	//create hook
	//hooks the module's methods to the module mgr's cached module obj
	//to use the module, one must go through the module mgr
	//he must first get the module through module manager, then use the obj through module mgr's cached obj
	//this means, when this module is used, the module manager has already verified that all resources in the manifest has been loaded
	//so within the hook, we do not need to worry about resource
	//and we can simply assume all necessary resource indicated in the manifest are loaded
	mM.hooks[HOOK_NAME] = function(m) {
		var obj = m.obj = {};

		//The pie slice element is for a single piece of pie,
		//if you only need a single piece, eg. a circular progress bar, you can just use the pie piece
		//the pie element is for multiple slices to form a single pie chart
		
		//properties
		//	startPercent:		default 0
		//	amtPercent:			0 to 1
		//						default 0 (the size of the slice)
		//	label:				default ''
		//	defaultTrans:		whether to enable default css transition
		//						default true
		//		default trans is 1s ease-out
		//	customTransCss:		css (eg. '1s ease-out') to attach to the animated parts, will disable default trans
		//						default ''
		//	intro:				play intro when the element is loaded
		//						default true
		//	initClosed:			slice stay at 0 percent from the start, set to false will disable intro
		//						default true
		//	clss:				extra class for identifying the pie-slice-cnr uniquely
		//						default ''
		//	onClk:				function for click evt, evt obj is passed
		//						default empty function
		//	onMseEtr:			function for mouseenter evt (hover), evt obj is passed
		//						default empty function
		//		these gives you js access to the event in case you can not style the events using css alone
		//	pointerEvts:		whether enable pointer events, set to false will disable above 2 functions
		//						default true
		//	ctrDistance:		the distance the slice should be from the center
		//						css values with units, if in percentage, is relative to the height of slice-cnr
		//						default 0
		//	defaultCtrTrans:	whether to enable default transition for center distance change
		//						default true
		//		default trans is 1s ease-out
		//	customCtrTransCss:	css (eg. '1s ease-out') to attach to the element for center distance for a transition, will disable default trans
		//						default ''
		//	ctrHoleRadius:		radius of making a hole in the center
		//						0 to 1 (percentage)
		//						default 0 (no hole)
		//	
		//APIs
		//	css
		//		pie-slice-cnr:						the entire slice
		//			use this to manipulate the entire slice, hover, click, etc.
		//			change its position, by default, it's positioned absolutely covering all four sides
		//			the cnr is a square
		//		pie-slice-cnr .wpr:					a transform rotated wrapper
		//			not very useful, but just in case you need to style it
		//			the wpr is a circle
		//		pie-slice-cnr .slice:				actual slice pieces
		//			use this to give it color, etc. you can not give it a shadow or stroke since they are separate pieces
		//			use filter drop-shadow on the cnr or box-shadow on wpr for a full circle

		//	react object
		//		The react object is attached to the element it creates as reactObj
		//		elm.reactObj exposes the object, you can then use the object's methods
		//		intro():					introes the slice, releasing it from it's 0 percent position
		//									useful when intro is disabled in property, otherwise you have to reset to 0 % position before you use it
		//		setStartPercent(sp):		set a new start percent
		//									0 to 1
		//		setAmtPercent(ap):			set a new amount percent
		//									0 to 1
		//		setCtrDistance(css):		sets the center distance in css value
		//		setCtrHoleRadius(radius):	sets the radius to new value, can be 0
		//									0 to 1
		//			the ctrHoleRadius must be set the in properties, otherwise this method will error
		//			this functionality is rarely used, so will not be improved unless absolutely needed
		//		rmvTrans():					removes the slice start percent and amt percent transition
		//		setTrans(css):				set the trans to css
		//									css e.g '1s ease-out'
		//		rmvCtrTrans():				removes the transition for center distance
		//		setCtrTrans(css):			set transition to css
		//									css e.g '1s ease-out'

		//parameters
		//	startPercent:	default 0
		//	amtPercent:		0 to 1, default 0 (the size of the slice)
		//	label:			default ''
		//	clss:			extra class for identifying the slice uniquely, default ''
		//	clk:			function for click evt, evt obj is passed
		//APIs
		//	css
		//		pie-slice-cnr:		the entire slice
		//			use this to manipulate the entire slice, hover, click, etc.
		//		slice:				actual slice pieces
		//			use this to give it color, etc.
		//	react object
		//		The react object is attached to the element it creates as reactThis
		//		elm.reactThis exposes the object, you can then use the object's methods
		//
		
		//React elements must be Capitalized,
		//so in order to use this, you will have to assign it to a variable
		//for example, var Pie = obj.pie
		//then you can use: <Pie />
		obj.Pie = React.createClass({
			render: function() {
				
			}
		});
	};
	
	shpsCmm.domReady().then(function() {
		ReactDOM.render(
			<PieSlice startPercent={0} amtPercent={1} ctrDistance="10%" ctrHoleRadius={.8}/>,
			document.body.children[0]
		);
	});
})();