#!/usr/bin/env php
<?php

/**
 * Modified wordpress makepot script for this plugin
 * Based on: https://github.com/wp-mirrors/wp-i18n-tools
 */

require_once dirname( __FILE__ ) . '/includes/po.php';
require_once dirname( __FILE__ ) . '/includes/pot-ext-meta.php';
require_once dirname( __FILE__ ) . '/includes/extract.php';

if ( !defined( 'STDERR' ) ) {
	define( 'STDERR', fopen( 'php://stderr', 'w' ) );
}

class MakePOT {
	var $max_header_lines = 30;

	var $rules = array(
		'_' => array('string'),
		'__' => array('string'),
		'_e' => array('string'),
		'_c' => array('string'),
		'_n' => array('singular', 'plural'),
		'_n_noop' => array('singular', 'plural'),
		'_nc' => array('singular', 'plural'),
		'__ngettext' => array('singular', 'plural'),
		'__ngettext_noop' => array('singular', 'plural'),
		'_x' => array('string', 'context'),
		'_ex' => array('string', 'context'),
		'_nx' => array('singular', 'plural', null, 'context'),
		'_nx_noop' => array('singular', 'plural', 'context'),
		'_n_js' => array('singular', 'plural'),
		'_nx_js' => array('singular', 'plural', 'context'),
		'esc_attr__' => array('string'),
		'esc_html__' => array('string'),
		'esc_attr_e' => array('string'),
		'esc_html_e' => array('string'),
		'esc_attr_x' => array('string', 'context'),
		'esc_html_x' => array('string', 'context'),
		'comments_number_link' => array('string', 'singular', 'plural'),
	);

	var $meta = array(
		'default' => array(
			'from-code' => 'utf-8',
			'msgid-bugs-address' => 'http://wppolyglots.wordpress.com',
			'language' => 'php',
			'add-comments' => 'translators',
			'comments' => "Copyright (C) {year} {package-name}\nThis file is distributed under the same license as the {package-name} package.",
		),
		'generic' => array(),
		'wp-frontend' => array(
			'description' => 'Translation of frontend strings in WordPress {version}',
			'copyright-holder' => 'WordPress',
			'package-name' => 'WordPress',
			'package-version' => '{version}',
		),
		'wp-admin' => array(
			'description' => 'Translation of site admin strings in WordPress {version}',
			'copyright-holder' => 'WordPress',
			'package-name' => 'WordPress',
			'package-version' => '{version}',
		),
		'wp-network-admin' => array(
			'description' => 'Translation of network admin strings in WordPress {version}',
			'copyright-holder' => 'WordPress',
			'package-name' => 'WordPress',
			'package-version' => '{version}',
		),
		'wp-core' => array(
			'description' => 'Translation of WordPress {version}',
			'copyright-holder' => 'WordPress',
			'package-name' => 'WordPress',
			'package-version' => '{version}',
		),
		'wp-ms' => array(
			'description' => 'Translation of multisite strings in WordPress {version}',
			'copyright-holder' => 'WordPress',
			'package-name' => 'WordPress',
			'package-version' => '{version}',
		),
		'wp-tz' => array(
			'description' => 'Translation of timezone strings in WordPress {version}',
			'copyright-holder' => 'WordPress',
			'package-name' => 'WordPress',
			'package-version' => '{version}',
		),
		'bb' => array(
			'description' => 'Translation of bbPress',
			'copyright-holder' => 'bbPress',
			'package-name' => 'bbPress',
		),
		'wp-plugin' => array(
			'description' => 'Translation of the WordPress plugin {name} {version} by {author}',
			'msgid-bugs-address' => 'http://wordpress.org/tag/{slug}',
			'copyright-holder' => '{author}',
			'package-name' => '{name}',
			'package-version' => '{version}',
		),
		'wp-theme' => array(
			'description' => 'Translation of the WordPress theme {name} {version} by {author}',
			'msgid-bugs-address' => 'http://wordpress.org/tags/{slug}',
			'copyright-holder' => '{author}',
			'package-name' => '{name}',
			'package-version' => '{version}',
			'comments' => 'Copyright (C) {year} {author}\nThis file is distributed under the same license as the {package-name} package.',
		),
		'bp' => array(
			'description' => 'Translation of BuddyPress',
			'copyright-holder' => 'BuddyPress',
			'package-name' => 'BuddyPress',
		),
		'glotpress' => array(
			'description' => 'Translation of GlotPress',
			'copyright-holder' => 'GlotPress',
			'package-name' => 'GlotPress',
		),
		'wporg-bb-forums' => array(
			'description' => 'WordPress.org International Forums',
			'copyright-holder' => 'WordPress',
			'package-name' => 'WordPress.org International Forums',
		),
		'rosetta' => array(
			'description' => 'Rosetta (.wordpress.org locale sites)',
			'copyright-holder' => 'WordPress',
			'package-name' => 'Rosetta',
		),
	);

	function __construct($deprecated = true) {
		$this->extractor = new StringExtractor( $this->rules );
	}

	function realpath_missing($path) {
		return realpath(dirname($path)).DIRECTORY_SEPARATOR.basename($path);
	}

	function xgettext($project, $dir, $output_file, $placeholders = array(), $excludes = array(), $includes = array()) {
		$meta = array_merge( $this->meta['default'], $this->meta[$project] );
		$placeholders = array_merge( $meta, $placeholders );
		$meta['output'] = $this->realpath_missing( $output_file );
		$placeholders['year'] = date( 'Y' );
		$placeholder_keys = array_map( create_function( '$x', 'return "{".$x."}";' ), array_keys( $placeholders ) );
		$placeholder_values = array_values( $placeholders );
		foreach($meta as $key => $value) {
			$meta[$key] = str_replace($placeholder_keys, $placeholder_values, $value);
		}

		$originals = $this->extractor->extract_from_directory( $dir, $excludes, $includes );
		$pot = new PO;
		$pot->entries = $originals->entries;

		$pot->set_header( 'Project-Id-Version', $meta['package-name'].' '.$meta['package-version'] );
		$pot->set_header( 'Report-Msgid-Bugs-To', $meta['msgid-bugs-address'] );
		$pot->set_header( 'POT-Creation-Date', gmdate( 'Y-m-d H:i:s+00:00' ) );
		$pot->set_header( 'MIME-Version', '1.0' );
		$pot->set_header( 'Content-Type', 'text/plain; charset=UTF-8' );
		$pot->set_header( 'Content-Transfer-Encoding', '8bit' );
		$pot->set_header( 'PO-Revision-Date', date( 'Y') . '-MO-DA HO:MI+ZONE' );
		$pot->set_header( 'Last-Translator', 'FULL NAME <EMAIL@ADDRESS>' );
		$pot->set_header( 'Language-Team', 'LANGUAGE <LL@li.org>' );
		$pot->set_comment_before_headers( $meta['comments'] );
		$pot->export_to_file( $output_file );
		return true;
	}


	function get_first_lines($filename, $lines = 30) {
		$extf = fopen($filename, 'r');
		if (!$extf) return false;
		$first_lines = '';
		foreach(range(1, $lines) as $x) {
			$line = fgets($extf);
			if (feof($extf)) break;
			if (false === $line) {
				return false;
			}
			$first_lines .= $line;
		}
		return $first_lines;
	}


	function get_addon_header($header, &$source) {
		if (preg_match('|'.$header.':(.*)$|mi', $source, $matches))
			return trim($matches[1]);
		else
			return false;
	}

	function run($dir, $output, $slug) {
		$placeholders = array();
		$main_file = $dir.'/'.$slug.'.php';
		if(!is_file($main_file)) $main_file = $dir.'/plugin.php';
		$source = $this->get_first_lines($main_file, $this->max_header_lines);

		$placeholders['version'] = $this->get_addon_header('Version', $source);
		$placeholders['author'] = $this->get_addon_header('Author', $source);
		$placeholders['name'] = $this->get_addon_header('Plugin Name', $source);
		$placeholders['slug'] = $slug;

		$output = is_null($output)? "$slug.pot" : $output;
		$res = $this->xgettext('wp-plugin', $dir, $output, $placeholders, array('(?:.+?/)?\.~build/.*', '(?:.+?/)?vendor/.*'));
		if (!$res) return false;
		$potextmeta = new PotExtMeta;
		$res = $potextmeta->append($main_file, $output);
		/* Adding non-gettexted strings can repeat some phrases */
		$output_shell = escapeshellarg($output);
		system("msguniq $output_shell -o $output_shell --no-wrap");
		return $res;
	}

}

if ((defined('IS_PHAR_MAKEPOT') && IS_PHAR_MAKEPOT) || (($included_files = get_included_files()) && $included_files[0] == __FILE__)) {
	$makepot = new MakePOT;

	if (4 == count($argv)) {
		$res = $makepot->run(realpath($argv[1]), $argv[2], $argv[3]);
		if (false === $res) {
			fwrite(STDERR, "Couldn't generate POT file!\n");
		}
	} else {
		$usage  = "Usage: php makepot.php DIRECTORY OUTPUT DOMAIN\n\n";
		$usage .= "Generate POT file from the files in DIRECTORY [OUTPUT]\n";

		fwrite(STDERR, $usage);
		exit(1);
	}
}
