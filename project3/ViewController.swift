//
//  ViewController.swift
//  project3
//
//  Created by Aron Gates on 1/24/18.
//  Copyright © 2018 Aron Gates. All rights reserved.
//

import UIKit
import GoogleMaps

class ViewController: UIViewController, GMSPanoramaViewDelegate  {
    
    func panoramaView(_ view: GMSPanoramaView, didMoveTo panorama: GMSPanorama?) {
        print(panorama?.coordinate as Any)
    }
    
    func panoramaView(_ panoramaView: GMSPanoramaView, didTap marker: GMSMarker) -> Bool {
        print("YOYOYO")
        
        return true
    }

    override func loadView() {
        let panoView = GMSPanoramaView(frame: .zero)
        panoView.delegate = self
        self.view = panoView
        
        // Create a marker in paris
        let position = CLLocationCoordinate2D(latitude: 48.858, longitude: 2.284)
        let marker = GMSMarker(position: position)
        marker.title = "Hello World"
        marker.snippet = "Population: 8,174,100"
        marker.zIndex = 100
        
        
        // Add the marker to a GMSPanoramaView object named panoView
        marker.panoramaView = panoView
        
        panoView.moveNearCoordinate(CLLocationCoordinate2D(latitude: 48.858, longitude: 2.284))
    }
    
}

