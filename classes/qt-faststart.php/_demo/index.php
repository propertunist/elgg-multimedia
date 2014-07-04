<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * qt-faststart.php
 *
 * In H.264-based video formats (mp4, m4v) the metadata is called a "moov atom".
 * The moov atom is a part of the file that holds the index information for the
 * whole file. Many encoding software programs such as FFMPEG will insert this
 * moov atom information at the end of the video file. This is bad. The moov atom
 * needs to be located at the beginning of the file, or else the entire file will
 * have to be downloaded before it begins playing
 * (http://flowplayer.org/plugins/streaming/pseudostreaming.html).
 *
 * this source is based on sources and/or informations from the following sources:
 *
 * php-reader - http://code.google.com/p/php-reader/
 * by Sven Vollbehr <sven@vollbehr.eu>
 * [http://code.google.com/p/php-reader/people/detail?u=svollbehr]
 *
 * php-mp4info - http://code.google.com/p/php-mp4info/)
 * by Tommy Lacroix <lacroix.tommy@gmail.com>
 * [http://www.tommylacroix.com]
 *
 * QTIndexSwapper (Original Application) - http://renaun.com/air/QTIndexSwapper.air
 * by Renaun Erickson (Adobe)
 * [http://renaun.com]
 *
 * qt-faststart.c - http://cekirdek.pardus.org.tr/~ismail/ffmpeg-docs/qt-faststart_8c-source.html
 * by Mike Melanson (Adobe) <melanson@pcisys.net>
 * [http://blogs.adobe.com/penguin.swf/]
 *
 * PHP versions 5
 *
 * LICENSE: qt-faststart.php - Copyright (c) 2009, Benjamin Carl -
 * All rights reserved. Redistribution and use in source and binary forms, with
 * or without modification, are permitted provided that the following conditions
 * are met: Redistributions of source code must retain the above copyright notice,
 * this list of conditions and the following disclaimer. * Redistributions in binary
 * form must reproduce the above copyright notice, this list of conditions and the
 * following disclaimer in the documentation and/or other materials provided with
 * the distribution. * All advertising materials mentioning features or use of
 * this software must display the following acknowledgement: This product includes
 * software developed by Benjamin Carl and its contributors.
 *
 * Neither the name of Benjamin Carl nor the names of its contributors may be used
 * to endorse or promote products derived from this software without specific prior
 * written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
 * ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR
 * ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON
 * ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 * Please feel free to contact us via e-mail: phpfluesterer@googlemail.com
 *
 * @category   Qtfaststart
 * @package    Qtfaststart_Lib
 * @subpackage Qtfaststart_Lib_Demo
 * @author     Benjamin Carl <phpfluesterer@googlemail.com>
 * @copyright  2009 Benjamin Carl
 * @license    http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @version    git: $Id: $
 * @link       https://github.com/clickalicious/qt-faststart.php
 * @see        -
 * @since      File available since Release 1.0.0
 */

require_once '../lib/Qtfaststart.class.php';


/**
 * filenames
 */
// input
$inFile  = PATH_QTFASTSTART.'../_demo/demo.mp4';
// output (processed file)
$outFile = PATH_QTFASTSTART.'../_demo/demo_fixed.mp4';


/**
 * instanciate qt-faststart.php
 */
$qtfaststart = Qtfaststart::getInstance();


echo '<pre>';

/**
 * read file, preprocess (parse atoms/boxes)
 */
$result = $qtfaststart->setInput($inFile);
if ($result !== true) {
    die($result);
}

/**
 * set the output filename and path
 */
$result = $qtfaststart->setOutput($outFile);
if ($result !== true) {
    die($result);
}

/**
 * moov positioning fix
 */
$result = $qtfaststart->fix();
if ($result !== true) {
    echo $result;
} else {
    echo 'moov atom location successfuly moved to top of file!<br />result: '.$outFile;
}

echo '</pre>';

?>
