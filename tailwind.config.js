module.exports = {
	mode: "jit",
	purge: [
		"./application/views/**/*.php",
		"./application/modules/**/*.php",
		"./application/config/**/*.php",
		"./application/controllers/**/*.php",
	],
	darkMode: "class", // or 'media' or 'class'
	theme: {
		extend: {},
	},
	variants: {
		extend: {},
	},
	plugins: [require("@tailwindcss/forms")],
};
