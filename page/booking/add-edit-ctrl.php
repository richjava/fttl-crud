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
    $userId = 1;//hard-coded because we don't have a logged in user yet
    $booking->setUserId($userId);
}

//if (array_key_exists('cancel', $_POST)) {
//    // redirect
//    Utils::redirect('detail', array('id' => $booking->getId()));
//} 
//else
    if (array_key_exists('save', $_POST)) {
    // for security reasons, do not map the whole $_POST['todo']
    $data = array(
        'flight_name' => $_POST['booking']['flight_name'],
        'flight_date' => $_POST['booking']['flight_date']
    );

    // map
    BookingMapper::map($booking, $data);
    // validate
    //$errors = BookingValidator::validate($booking);
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
