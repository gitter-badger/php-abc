<?php
//----------------------------------------------------------------------------------------------------------------------
ini_set( 'session.use_only_cookies', 1 );
ini_set( 'url_rewriter.tags', "" );
mb_internal_encoding( "UTF-8" );
date_default_timezone_set( 'Europe/Amsterdam' );

//----------------------------------------------------------------------------------------------------------------------
define( 'HOME', realpath( __DIR__.'/..' ) );

// Directory where errors are stored.
define( 'DIR_ERROR', HOME.'/var/err' );

// Directory where errors are stored.
define( 'DIR_TMP', HOME.'/var/tmp' );

//----------------------------------------------------------------------------------------------------------------------
const ICON_SMALL_BABEL_FISH = '/images/abc/icons/12x12/babel_fish.png';
const ICON_SMALL_DELETE     = '/images/abc/icons/12x12/delete.png';
const ICON_SMALL_DETAILS    = '/images/abc/icons/12x12/details.png';
const ICON_SMALL_EDIT       = '/images/abc/icons/12x12/edit.png';
const ICON_SMALL_FALSE      = '/images/abc/icons/12x12/noapply.png';
const ICON_SMALL_NO_DELETE  = '/images/abc/icons/12x12/no_delete.png';
const ICON_SMALL_NO_PUBLISH = '/images/icons/12x12/no_publish.png';
const ICON_SMALL_OK         = '/images/abc/icons/12x12/ok.png';
const ICON_SMALL_PUBLISH    = '/images/icons/12x12/publish.png';
const ICON_SMALL_TRUE       = '/images/abc/icons/12x12/apply.png';

const ICON_SEPARATOR  = '/images/abc/icons/16x16/separator.png';
const ICON_ADD        = '/images/abc/icons/16x16/add.png';
const ICON_EDIT       = '/images/abc/icons/16x16/edit.png';
const ICON_BABEL_FISH = '/images/abc/icons/16x16/babel_fish.png';
const ICON_UPLOAD     = '/images/abc/icons/16x16/upload.png';

const ICON_MIME_CSV = '/images/mime/16x16/csv.png';

//----------------------------------------------------------------------------------------------------------------------
