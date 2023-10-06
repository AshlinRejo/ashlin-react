const { render } = wp.element;
import App from './App';

var element = document.getElementById( 'ashlin-react-app' );
if( 'undefined' !== typeof element && null !== element ) {
    render(<App />, document.getElementById('ashlin-react-app'));
}