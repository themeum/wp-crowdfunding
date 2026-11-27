/**
 * Set the WPCF_ENV constant in a copy of wp-crowdfunding.php.
 *
 * CLI:    node dev/scripts/set-env.js <development|production> [file]
 * Module: require('./dev/scripts/set-env')(env, file)
 *
 * The file defaults to the source wp-crowdfunding.php. Throws when the
 * define() line is missing, so a build never ships with the wrong value.
 */
const fs = require('fs');
const path = require('path');

const ENVS = ['development', 'production'];
const DEFAULT_FILE = path.join(__dirname, '..', '..', 'wp-crowdfunding.php');
const PATTERN = /(define\(\s*'WPCF_ENV',\s*)'([a-z]+)'(\s*\))/;

function setEnv(env, file = DEFAULT_FILE) {
	if (!ENVS.includes(env)) {
		throw new Error(`WPCF_ENV must be one of: ${ENVS.join(', ')} (got "${env}")`);
	}
	const source = fs.readFileSync(file, 'utf8');
	const match = source.match(PATTERN);
	if (!match) {
		throw new Error(`define( 'WPCF_ENV', ... ) not found in ${file}`);
	}
	if (match[2] === env) {
		console.log(`WPCF_ENV is already ${env}`);
		return false;
	}
	fs.writeFileSync(file, source.replace(PATTERN, `$1'${env}'$3`));
	console.log(`WPCF_ENV changed from ${match[2]} to ${env}`);
	return true;
}

module.exports = setEnv;

if (require.main === module) {
	try {
		setEnv(process.argv[2], process.argv[3] || DEFAULT_FILE);
	} catch (err) {
		console.error(err.message);
		process.exit(1);
	}
}
