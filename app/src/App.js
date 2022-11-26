import Home from './pages/Home';
import Login from './pages/Login';
import Register from './pages/Register';
import Reset from './pages/Reset';

import { BrowserRouter, Routes, Route } from 'react-router-dom';

const App = () => {
	return (
		<div className="App">
			<BrowserRouter>
			<Routes>
				<Route path='/' element={<Home />} />
				<Route path='/login' element={<Login />} />
				<Route path='/register' element={<Register />} />
				<Route path='/reset' element={<Reset />} />
				<Route path='/reset/:token' element={<Reset />} />
			</Routes>
			</BrowserRouter>
		</div>
	);
}

export default App;
