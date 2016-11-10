<?php

/*
 * DO NOT ALTER OR REMOVE COPYRIGHT NOTICES OR THIS HEADER.
 *
 * Copyright 2011 Oracle and/or its affiliates. All rights reserved.
 *
 * Oracle and Java are registered trademarks of Oracle and/or its affiliates.
 * Other names may be trademarks of their respective owners.
 *
 * The contents of this file are subject to the terms of either the GNU
 * General Public License Version 2 only ("GPL") or the Common
 * Development and Distribution License("CDDL") (collectively, the
 * "License"). You may not use this file except in compliance with the
 * License. You can obtain a copy of the License at
 * http://www.netbeans.org/cddl-gplv2.html
 * or nbbuild/licenses/CDDL-GPL-2-CP. See the License for the
 * specific language governing permissions and limitations under the
 * License.  When distributing the software, include this License Header
 * Notice in each file and include the License file at
 * nbbuild/licenses/CDDL-GPL-2-CP.  Oracle designates this
 * particular file as subject to the "Classpath" exception as provided
 * by Oracle in the GPL Version 2 section of the License file that
 * accompanied this code. If applicable, add the following below the
 * License Header, with the fields enclosed by brackets [] replaced by
 * your own identifying information:
 * "Portions Copyrighted [year] [name of copyright owner]"
 *
 * If you wish your version of this file to be governed by only the CDDL
 * or only the GPL Version 2, indicate your decision by adding
 * "[Contributor] elects to include this software in this distribution
 * under the [CDDL or GPL Version 2] license." If you do not indicate a
 * single choice of license, a recipient has the option to distribute
 * your version of this file under either the CDDL, the GPL Version 2 or
 * to extend the choice of license to its licensees as provided above.
 * However, if you add GPL Version 2 code and therefore, elected the GPL
 * Version 2 license, then the option applies only if the new code is
 * made subject to such option by the copyright holder.
 *
 * Contributor(s):
 *
 * Portions Copyrighted 2011 Sun Microsystems, Inc.
 */
$headTemplate = new HeadTemplate('Add/Edit | TodoList', 'Edit or add a booking');

$errors = array();
$booking = null;
$edit = array_key_exists('id', $_GET);
$flightNames = array('Glider', 'Helicopter sightseeing');

function resize($image) {
    try {
        if ($image->resize()) {
            return true;
        } else {
            $data['image_url'] = false; //Image upload failed
        }
    } catch (Exception $ex) {
        //set placeholder for validator to recognise as an error
        $data['image_url'] = false; //Image upload failed
    }
}

if ($edit) {
    $dao = new BookingDao();
    $booking = Utils::getObjByGetId($dao);
} else {
    // set defaults
    $booking = new Booking();
    $booking->setFlightName('');
    $flightDate = new DateTime("+1 day");
    $flightDate->setTime(0, 0, 0);
    $booking->setFlightDate($flightDate);
    $booking->setStatus('pending');
    $userId = 1; //hard-coded because we don't have a logged in user yet
    $booking->setUserId($userId);
}

if (array_key_exists('save', $_POST)) {

    //filter input    
    $data = filter_input(INPUT_POST, 'booking', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
    //workaround for date
    $data['flight_date'].= ' 00:00:00';

    //image upload and resize
    // If the file name is available first upload it
    if (!empty($_FILES)) {
        if ($_FILES['myfile1']['name']) {
            $name = $_FILES['myfile1']['name'];
            $upload = new Uploader('myfile1');
            $filePath = $upload->upload();
            $pathPrefix = 'img/upload';
            $data['image_url'] = $filePath ? $filePath : '-1';
            if ($filePath && $upload->getType() != Uploader::PDF_TYPE) {
                // Successfully uploaded so resize now.
                $image = new ImageResizer($filePath, 100, $pathPrefix, "thumb");
                $data['image_url'] = resize($image) === true ? $name : '-1';
            } else {
                echo "Unable to upload file - SEE the ERROR ABOVE?<br />";
            }
        }
    }

    // map
    BookingMapper::map($booking, $data);
    // validate
    $errors = BookingValidator::validate($booking);
    // validate
    if (empty($errors)) {
        // save
        $dao = new BookingDao();
        $booking = $dao->save($booking);
        Flash::addFlash('Booking saved successfully.');
        // redirect
        Utils::redirect('list', array('module' => 'booking'));
    }
}
